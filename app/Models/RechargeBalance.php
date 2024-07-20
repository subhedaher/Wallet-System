<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeBalance extends Model
{
    use HasFactory;

    function shipping_point()
    {
        return $this->belongsTo(ShippingPoint::class, 'shipping_point_id', 'id');
    }

    function user()
    {
        return $this->belongsTo(user::class, 'user_id', 'id');
    }
}
