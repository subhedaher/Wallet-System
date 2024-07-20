<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentTransactionsResource;
use App\Models\PaymentOfBills;
use Illuminate\Http\Request;

class PaymentTransactionController extends Controller
{
    public function paymentTransactions()
    {
        $id = Auth()->user()->id;
        $paymentTransactions = PaymentOfBills::where('user_id', '=', $id)->with('payment_provider')->get();
        $recourse = PaymentTransactionsResource::collection($paymentTransactions);
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $recourse
        ]);
    }
}
