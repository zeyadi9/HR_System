<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{
    use LogsIpAddress;

    protected $fillable = ['user_id', 'name', 'evaluation', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
