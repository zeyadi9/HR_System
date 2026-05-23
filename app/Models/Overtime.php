<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use LogsIpAddress;

    protected $fillable = [
        'user_id',
        'date',
        'day',
        'name',
        'total_hours',
        'reason',
        'from',
        'to',
        'status',
        'refuse_reason',
        'actioned_by'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
