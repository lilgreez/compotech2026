<?php

namespace App\Models;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterInspection extends Model
{
    use HasFactory, SoftDeletes;

    // ANDREW FIX: Menambahkan 'type' agar bisa disimpan
    protected $fillable =[
        'type',
        'name',
        'description',
        'part_id',
        'status',
    ];

    protected static function booted(): void
    {
        static::observe(AuditObserver::class);
    }

    public function part()
    {
        return $this->belongsTo(MasterPart::class);
    }
}