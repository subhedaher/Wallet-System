<?php

namespace App\Http\Controllers;

use App\Models\PaymentOfBills;
use App\Models\PaymentProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentOfBillController extends Controller
{
    public function paymentOfBills(Request $request)
    {
        $validator = Validator($request->all(), [
            'balance' => 'required|numeric',
            'details' => 'required|string|max:45',
            'payment_provider_id' => 'required|string|exists:payment_providers,id'
        ]);

        $sender = User::where('id', '=', Auth()->user()->id)->first();
        if (!$validator->fails()) {
            $payment_provider = PaymentProvider::where('id', '=', $request->input('payment_provider_id'))->first();
            if ($sender->balance >= $request->input('balance')) {
                if ($request->input('balance') > 0) {
                    $sender->balance -= $request->input('balance');
                    $isSaved = $sender->save();
                    if ($isSaved) {
                        $paymentOfBills = new PaymentOfBills();
                        $paymentOfBills->balance = $request->input('balance');
                        $paymentOfBills->user_id = $sender->id;
                        $paymentOfBills->payment_provider_id = $payment_provider->id;
                        $paymentOfBills->details = $request->input('details');
                        $paymentOfBills->save();
                        return response()->json([
                            'status' => true,
                            'message' => 'Successfully Payment'
                        ], Response::HTTP_OK);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Failed Payment'
                        ], Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Balance Invalid'
                    ], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The balance is not enough'
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
