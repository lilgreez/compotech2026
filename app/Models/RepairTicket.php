<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepairTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'dieset_id',
        'part_id',
        'reporter_user_id',
        'technician_user_id',
        'issue_description',
        'priority',
        'evidence_photo',
        'status',
        'progress_percent',
        'technician_notes',
        'resolved_at',
    ];

    protected $casts = [
        'progress_percent' => 'integer',
        'resolved_at' => 'datetime',
    ];

    public function dieset()
    {
        return $this->belongsTo(MasterDieset::class, 'dieset_id');
    }

    public function part()
    {
        return $this->belongsTo(MasterPart::class, 'part_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_user_id');
    }

    public function logs()
    {
        return $this->hasMany(RepairTicketLog::class)->orderBy('created_at', 'desc');
    }
}
