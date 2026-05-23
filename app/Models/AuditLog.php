<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_name',
        'action',
        'module',
        'target',
        'details',
    ];

    /**
     * Helper to log an action easily.
     */
    public static function log($userName, $action, $module, $target, $details = null)
    {
        return self::create([
            'user_name' => $userName,
            'action'    => $action,
            'module'    => $module,
            'target'    => $target,
            'details'   => $details,
        ]);
    }
}
