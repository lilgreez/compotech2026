<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairTicketLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_ticket_id',
        'user_id',
        'action',
        'note',
    ];

    public function ticket()
    {
        return $this->belongsTo(RepairTicket::class, 'repair_ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
