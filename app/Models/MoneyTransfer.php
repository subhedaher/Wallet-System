<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTransfer extends Model
{
    use HasFactory;

    function sendUser()
    {
        return $this->belongsTo(User::class, 'send_user_id', 'id');
    }

    function receiveUser()
    {
        return $this->belongsTo(User::class, 'receive_user_id', 'id');
    }
}