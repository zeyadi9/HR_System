<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNote extends Model
{
    use LogsIpAddress;

    protected $fillable = ['note', 'date'];
}
