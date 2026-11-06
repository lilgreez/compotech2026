<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoldRequestMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'mold_request_id',
        'part_name',
        'required_qty',
        'available_qty',
        'status',
        'notes',
    ];

    protected $casts = [
        'required_qty' => 'integer',
        'available_qty' => 'integer',
    ];

    public function moldRequest()
    {
        return $this->belongsTo(MoldRequest::class);
    }
}
