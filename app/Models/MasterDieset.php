<?php

namespace App\Models;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDieset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =[
        'name',
        'product_code',
        'production_year',
        'total_shoot',
        'description',
        'status',
    ];

    protected static function booted(): void
    {
        static::observe(AuditObserver::class);
    }

    // Relasi ke Master Parts
    public function parts()
    {
        return $this->hasMany(MasterPart::class, 'dieset_id');
    }

    // ANDREW ARCHITECTURE FIX: 
    // Relasi ajaib untuk melompati tabel 'master_parts' dan langsung mengambil 'inspection_histories'
    // Sangat berguna untuk performa halaman Inspection Monitor (Hal 12 PDF)
    public function inspectionHistories()
    {
        return $this->hasManyThrough(
            InspectionHistory::class, 
            MasterPart::class,
            'dieset_id', // Foreign key di tabel master_parts
            'part_id',   // Foreign key di tabel inspection_histories
            'id',        // Local key di tabel master_diesets
            'id'         // Local key di tabel master_parts
        );
    }
}