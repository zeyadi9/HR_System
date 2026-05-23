<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use LogsIpAddress;

    protected $fillable = ['user_id', 'name', 'reason', 'amount', 'notes', 'actioned_by', 'status', 'refuse_reason'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}