<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use LogsIpAddress;

    protected $fillable = [
        'user_id', 'date', 'day', 'name',
        'reason', 'from', 'to',
        'status', 'refuse_reason',
        'permission_type', 'alarm', 'actioned_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
