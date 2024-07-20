<?php

namespace App\Http\Controllers;

use App\Mail\RechargeBalance as MailRechargeBalance;
use App\Models\RechargeBalance;
use App\Models\ShippingPoint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class RechargeBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('cms.rechargeBalance.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RechargeBalance $rechargeBalance)
    {
        //
    }

    public function search(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric|exists:users,phone_number'
        ]);

        $user = User::where('phone_number', '=', $request->input('phone_number'))->first();
        return redirect()->route('user.showShipping', $user);
    }

    public function showShipping(Request $request, User $user)
    {
        return view('cms.rechargeBalance.recharge', ['user' => $user]);
    }

    public function shipping(Request $request, $id)
    {
        $this->authorize('shipping', User::findOrFail($id));
        $shipping_id = Auth('shippingPoint')->user()->id;
        $shipping_point = ShippingPoint::where('id', '=', $shipping_id)->first();
        if ($shipping_point->status === 'w') {
            $validator = Validator($request->all(), [
                'name' => 'required|max:20',
                'balance' => 'required|numeric'
            ]);

            if (!$validator->fails()) {
                if ($request->input('balance') > 0) {
                    $user = User::where('id', '=', $id)->first();
                    $user->balance += $request->input('balance');
                    $isSaved = $user->save();
                    if ($isSaved) {
                        $rechargeBalance = new RechargeBalance();
                        $rechargeBalance->employee_name = $request->input('name');
                        $rechargeBalance->balance = $request->input('balance');
                        $rechargeBalance->user_id = $user->id;
                        $rechargeBalance->shipping_point_id = Auth('shippingPoint')->user()->id;
                        $rechargeBalance->save();
                        Mail::to($user->email)->send(new MailRechargeBalance(explode(' ', $user->full_name)[0], $request->input('balance'), $request->input('name'), Auth('shippingPoint')->user()->full_name));
                        return response()->json([
                            'status' => false,
                            'message' => 'Recharge Balance Successfully'
                        ], Response::HTTP_OK);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Recharge Balance Failed!'
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
                    'message' => $validator->getMessageBag()->first()
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'The Account Is Frozen'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
