<?php

namespace App\Models;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterInspection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'part_id',
        'status',
    ];

    protected static function booted(): void
    {
        static::observe(AuditObserver::class);
    }

    // Relationships
    public function part()
    {
        return $this->belongsTo(MasterPart::class);
    }
}