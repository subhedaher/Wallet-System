<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOfBills extends Model
{
    use HasFactory;

    function  payment_provider()
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id', 'id');
    }

    function  user()
    {
        return $this->belongsTo(user::class, 'user_id', 'id');
    }
}