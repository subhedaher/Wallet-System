<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShippingTransactionsResource;
use App\Models\PaymentProvider;
use App\Models\RechargeBalance;
use App\Models\ShippingPoint;
use GuzzleHttp\Psr7\Request;

class TransactionController extends Controller
{
    public function shippingTransactions()
    {
        $id = Auth()->user()->id;
        $shippingTransactions = RechargeBalance::where('user_id', '=', $id)->with('shipping_point')->get();
        $recourse = ShippingTransactionsResource::collection($shippingTransactions);
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $recourse
        ]);
    }

    public function allTransactions()
    {
        return view('cms.transactions.all');
    }

    public function shippingPointTransactions()
    {
        // alert
        $id = Auth()->user()->id;
        $transactions = RechargeBalance::where('shipping_point_id', '=', $id)->with('user')->orderBy('id', 'desc')->get();
        return view('cms.transactions.shippingPoint', ['transactions' => $transactions]);
    }


    public function userTransactions()
    {
        return view('cms.transactions.user');
    }

    public function paymentProviderTransactions()
    {
        $paymentProviders = PaymentProvider::all();

        return view('cms.transactions.paymentProvider', ['paymentProviders' => $paymentProviders]);
    }

    public function shippingPointTransactionsAdmin()
    {
        $shippingPoints = ShippingPoint::all();
        return view('cms.transactions.shippingPointAdmin', ['shippingPoints' => $shippingPoints]);
    }
}
