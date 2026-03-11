<?php

namespace App\Models;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailReport extends Model
{
    use HasFactory;

    protected $fillable =[
        'email',
        'full_name',
        'status',
    ];

    protected static function booted(): void
    {
        // Tetap pasang observer agar jika ada Admin yang mengubah email penerima, terekam di Audit Log!
        static::observe(AuditObserver::class);
    }
}