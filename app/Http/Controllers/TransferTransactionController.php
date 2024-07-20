<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransferMoneyTransactionResource;
use App\Models\MoneyTransfer;
use Illuminate\Http\Request;

class TransferTransactionController extends Controller
{
    public function transferTransactions()
    {
        $id = Auth()->user()->id;
        $MoneyTransferTransaction = MoneyTransfer::where('send_user_id', '=', $id)->orWhere('receive_user_id', '=', $id)->get();
        $recourse = TransferMoneyTransactionResource::collection($MoneyTransferTransaction);
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $recourse
        ]);
    }
}