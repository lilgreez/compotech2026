<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoldRequestStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'mold_request_id',
        'stage_name',
        'stage_order',
        'weight_percent',
        'status',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'weight_percent' => 'integer',
        'stage_order' => 'integer',
    ];

    public function moldRequest()
    {
        return $this->belongsTo(MoldRequest::class);
    }
}
