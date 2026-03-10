<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'operator_id',
        'inspection_date',
        'condition',
        'damage_details',
        'action_taken',
        'reason',
        'evidence_photo_path',
        'report_sent',
    ];

    protected $casts = [
        'inspection_date' => 'datetime',
        'report_sent' => 'boolean',
    ];

    public function part()
    {
        return $this->belongsTo(MasterPart::class, 'part_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
