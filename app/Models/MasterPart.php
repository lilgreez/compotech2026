<?php

namespace App\Models;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'part_code',
        'description',
        'dieset_id',
        'cavity_number',
        'current_stock',
        'image_path',
        'status',
    ];

    public function inspectionHistories()
    {
        return $this->hasMany(InspectionHistory::class, 'part_id');
    }

    protected static function booted(): void
    {
        static::observe(AuditObserver::class);
    }

    // Relationships
    public function dieset()
    {
        return $this->belongsTo(MasterDieset::class);
    }
}