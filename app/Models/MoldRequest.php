<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MoldRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'spk_number',
        'mold_name',
        'customer',
        'pic_name',
        'start_date',
        'target_date',
        'status',
        'total_progress',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'target_date' => 'date',
        'total_progress' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function stages()
    {
        return $this->hasMany(MoldRequestStage::class)->orderBy('stage_order', 'asc');
    }

    public function materials()
    {
        return $this->hasMany(MoldRequestMaterial::class);
    }

    /**
     * Hitung otomatis total progress berdasarkan bobot tahapan yang selesai
     */
    public function recalculateProgress(): void
    {
        $stages = $this->stages;
        $total = 0;
        foreach ($stages as $stage) {
            if ($stage->status === 'Completed') {
                $total += $stage->weight_percent;
            } elseif ($stage->status === 'In Progress') {
                $total += (int) round($stage->weight_percent / 2);
            }
        }

        $total = min(100, max(0, $total));
        $this->total_progress = $total;
        if ($total >= 100) {
            $this->status = 'Completed';
        } elseif ($total > 0 && $this->status !== 'Completed') {
            $this->status = 'In Progress';
        }
        $this->save();
    }
}
