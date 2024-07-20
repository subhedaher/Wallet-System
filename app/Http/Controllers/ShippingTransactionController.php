<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShippingTransactionsResource;
use App\Models\RechargeBalance;
use Illuminate\Http\Request;

class ShippingTransactionController extends Controller
{
    public function shippingTransactions()
    {
        $id = Auth()->user()->id;
        $rechargeBalanceTransactions = RechargeBalance::where('user_id', '=', $id)->get();
        $recourse = ShippingTransactionsResource::collection($rechargeBalanceTransactions);
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $recourse
        ]);
    }
}
