<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentCategory extends Model
{
    use HasFactory, SoftDeletes;

    function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    function payment_providers()
    {
        return $this->hasMany(PaymentProvider::class, 'payment_category_id', 'id');
    }
}
