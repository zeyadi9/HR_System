<?php

namespace App\Models;

trait LogsIpAddress
{
    protected static function bootLogsIpAddress()
    {
        static::creating(function ($model) {
            if (request()) {
                $model->created_ip = request()->ip();
            }
        });
    }
}
