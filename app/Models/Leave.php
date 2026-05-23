<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use LogsIpAddress;

    protected $fillable = [
        'user_id', 'date', 'day', 'name',
        'reason', 'substitute', 'days_count',
        'status', 'refuse_reason', 'actioned_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
