<?php

namespace App\Http\Controllers;

use App\Models\MoneyTransfer;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MoneyTransferController extends Controller
{
    public function moneyTransfer(Request $request)
    {
        $validator = Validator($request->all(), [
            'phone_number' => 'required|string|exists:users,phone_number',
            'balance' => 'required|numeric',
            'details' => 'nullable|string|max:45'
        ]);

        $sender = User::where('id', '=', Auth()->user()->id)->first();
        if (!$validator->fails()) {
            $user = User::where('phone_number', '=', $request->input('phone_number'))->first();
            if ($sender->balance >= $request->input('balance')) {
                if ($request->input('balance') > 0) {
                    $user->balance += $request->input('balance');
                    $isSaved = $user->save();
                    if ($isSaved) {
                        $sender->balance -= $request->input('balance');
                        $sender->save();
                        $moneyTransfer = new MoneyTransfer();
                        $moneyTransfer->balance = $request->input('balance');
                        $moneyTransfer->send_user_id = $sender->id;
                        $moneyTransfer->receive_user_id = $user->id;
                        $moneyTransfer->details = $request->input('details') ?? null;
                        $moneyTransfer->save();
                        return response()->json([
                            'status' => false,
                            'message' => 'Successfully Transfer'
                        ], Response::HTTP_OK);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Failed Transfer'
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
