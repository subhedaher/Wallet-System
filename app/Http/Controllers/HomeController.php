<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\PaymentCategory;
use App\Models\PaymentProvider;
use App\Models\ShippingPoint;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $admins = Admin::count();
        $paymentCategories = PaymentCategory::count();
        $paymentProviders = PaymentProvider::count();
        $shippingPonints = ShippingPoint::count();
        $users = User::count();
        return view('cms.index', [
            'admins' => $admins,
            'paymentCategories' => $paymentCategories,
            'paymentProviders' => $paymentProviders,
            'shippingPonints' => $shippingPonints,
            'users' => $users
        ]);
    }
}
