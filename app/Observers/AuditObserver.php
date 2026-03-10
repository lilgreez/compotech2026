<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->logAction($model, 'CREATE', null, $model->toArray());
    }

    public function updated(Model $model): void
    {
        $original = $model->getOriginal();
        $changes = $model->getChanges();

        if (!empty($changes)) {
            $this->logAction($model, 'UPDATE', $original, $model->toArray());
        }
    }

    public function deleted(Model $model): void
    {
        $this->logAction($model, 'DELETE', $model->toArray(), null);
    }

    private function logAction(Model $model, string $action, ?array $oldValues, ?array $newValues): void
    {
        // FIX ANDREW: Tidak lagi me-return (bypass) jika Auth::check() false!
        // Kita tangkap apakah ini eksekusi dari User, atau dari Sistem/Mesin.
        $userId = Auth::check() ? Auth::id() : null;
        $finalAction = Auth::check() ? $action : $action . '_SYSTEM';

        // Filter kolom yang tidak perlu diaudit
        $excludeFields =['created_at', 'updated_at', 'password', 'remember_token'];

        $filteredOld = $oldValues ? array_diff_key($oldValues, array_flip($excludeFields)) : null;
        $filteredNew = $newValues ? array_diff_key($newValues, array_flip($excludeFields)) : null;

        AuditLog::create([
            'user_id' => $userId,
            'action' => $finalAction,
            'table_name' => $model->getTable(),
            'record_id' => $model->getKey(),
            'old_values' => $filteredOld,
            'new_values' => $filteredNew,
        ]);
    }
}