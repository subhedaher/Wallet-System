<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class ShippingPoint extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, SoftDeletes, Notifiable, HasRoles;

    function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    function statusActive(): Attribute
    {
        return new Attribute(get: fn () => $this->status == 'w' ? 'works' : 'frozen');
    }

    function recharge_balances()
    {
        return $this->hasMany(RechargeBalance::class, 'shipping_point_id', 'id');
    }
}
