<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use LogsIpAddress;

    protected $fillable = ['user_id', 'name', 'note', 'date', 'day', 'status', 'accept_note', 'refuse_reason', 'actioned_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
