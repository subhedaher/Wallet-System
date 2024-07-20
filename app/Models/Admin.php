<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, SoftDeletes, Notifiable, HasRoles;

    function payment_categories()
    {
        return $this->hasMany(PaymentCategories::class, 'admin_id', 'id');
    }

    function payment_providers()
    {
        return $this->hasMany(PaymentProvider::class, 'admin_id', 'id');
    }

    function shipping_points()
    {
        return $this->hasMany(ShippingPoint::class, 'admin_id', 'id');
    }
}
