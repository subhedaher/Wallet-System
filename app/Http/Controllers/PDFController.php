<?php

namespace App\Http\Controllers;

use App\Models\MoneyTransfer;
use App\Models\PaymentOfBills;
use App\Models\PaymentProvider;
use App\Models\RechargeBalance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class PDFController extends Controller
{
    public function shippingPointTransactionDaily(Request $request)
    {
        $id = Auth()->user()->id;
        $twentyFourHoursAgo = Carbon::now()->subHours(24);
        $rechargeBalances = RechargeBalance::where('shipping_point_id', '=', $id)->where('created_at', '>=', $twentyFourHoursAgo)->with('user')->with('shipping_point')->get();
        $totalbalance = RechargeBalance::where('shipping_point_id', '=', $id)->where('created_at', '>=', $twentyFourHoursAgo)->sum('balance');
        $pdf = PDF::loadView('pdf.shippingPointTransaction', ['rechargeBalances' => $rechargeBalances, 'totalbalance' => $totalbalance]);
        return $pdf->download('report.pdf');
    }

    public function all(Request $request)
    {
        $validator = Validator($request->all(), [
            'from' => 'required|date',
            'to' => 'required|date'
        ]);
        if (!$validator->fails()) {
            if ($request->input('from') > $request->input('to')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Date is Invalid'
                ], Response::HTTP_BAD_REQUEST);
            } else {
                $rechargeBalances = RechargeBalance::whereBetween('created_at', [$request->input('from'), $request->input('to')])->with('user')->with('shipping_point')->get();
                $totalRechargeBalances = RechargeBalance::whereBetween('created_at', [$request->input('from'), $request->input('to')])->sum('balance');

                $payment_of_bills = PaymentOfBills::whereBetween('created_at', [$request->input('from'), $request->input('to')])->with('user')->get();
                $totalPayment_of_bills = PaymentOfBills::whereBetween('created_at', [$request->input('from'), $request->input('to')])->sum('balance');

                $money_transfers = MoneyTransfer::whereBetween('created_at', [$request->input('from'), $request->input('to')])->with('sendUser')->with('receiveUser')->get();
                $totalMoney_transfers = MoneyTransfer::whereBetween('created_at', [$request->input('from'), $request->input('to')])->sum('balance');


                $pdf = PDF::loadView(
                    'pdf.allTransaction',
                    [
                        'rechargeBalances' => $rechargeBalances,
                        'totalRechargeBalances' => $totalRechargeBalances,
                        'payment_of_bills' => $payment_of_bills,
                        'totalPayment_of_bills' => $totalPayment_of_bills,
                        'money_transfers' => $money_transfers,
                        'totalMoney_transfers' => $totalMoney_transfers
                    ]
                );
                return $pdf->download('report.pdf');
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function user(Request $request)
    {
        $validator = Validator($request->all(), [
            'phone_number' => 'required|string|exists:users,phone_number',
            'from' => 'required|date',
            'to' => 'required|date'
        ]);
        if (!$validator->fails()) {
            if ($request->input('from') > $request->input('to')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Date is Invalid'
                ], Response::HTTP_BAD_REQUEST);
            } else {
                $user = User::where('phone_number', '=', $request->input('phone_number'))->first();
                $money_transfers = MoneyTransfer::where('send_user_id', '=', $user->id)->whereBetween('created_at', [$request->input('from'), $request->input('to')])->with('sendUser')->with('receiveUser')->get();
                $totalMoney_transfers = MoneyTransfer::where('send_user_id', '=', $user->id)->whereBetween('created_at', [$request->input('from'), $request->input('to')])->sum('balance');

                $payment_of_bills = PaymentOfBills::where('user_id', '=', $user->id)->whereBetween('created_at', [$request->input('from'), $request->input('to')])->with('user')->get();
                $totalPayment_of_bills = PaymentOfBills::where('user_id', '=', $user->id)->whereBetween('created_at', [$request->input('from'), $request->input('to')])->sum('balance');

                $rechargeBalances = RechargeBalance::where('user_id', '=', $user->id)->whereBetween('created_at', [$request->input('from'), $request->input('to')])->with('user')->with('shipping_point')->get();
                $totalRechargeBalances = RechargeBalance::where('user_id', '=', $user->id)->whereBetween('created_at', [$request->input('from'), $request->input('to')])->sum('balance');

                $pdf = PDF::loadView(
                    'pdf.userTransaction',
                    [
                        'money_transfers' => $money_transfers,
                        'totalMoney_transfers' => $totalMoney_transfers,
                        'payment_of_bills' => $payment_of_bills,
                        'totalPayment_of_bills' => $totalPayment_of_bills,
                        'rechargeBalances' => $rechargeBalances,
                        'totalRechargeBalances' => $totalRechargeBalances
                    ]
                );
                return $pdf->download('report.pdf');
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function paymentProvider(Request $request)
    {
        $validator = Validator($request->all(), [
            'paymentProvider' => 'required|numeric|exists:payment_providers,id',
            'from' => 'required|date',
            'to' => 'required|date'
        ]);
        if (!$validator->fails()) {
            if ($request->input('from') > $request->input('to')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Date is Invalid'
                ], Response::HTTP_BAD_REQUEST);
            } else {
                $payment_of_bills = PaymentOfBills::where('payment_provider_id', '=', $request->input('paymentProvider'))->whereBetween('created_at', [$request->input('from'), $request->input('to')])->with('user')->get();
                $total = PaymentOfBills::where('payment_provider_id', '=', $request->input('paymentProvider'))->whereBetween('created_at', [$request->input('from'), $request->input('to')])->sum('balance');
                $pdf = PDF::loadView('pdf.paymentProviderTransaction', ['payment_of_bills' => $payment_of_bills, 'total' => $total]);
                return $pdf->download('report.pdf');
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function shippingPoint(Request $request)
    {
        $validator = Validator($request->all(), [
            'shippingPoint' => 'required|numeric|exists:shipping_points,id',
            'from' => 'required|date',
            'to' => 'required|date'
        ]);
        if (!$validator->fails()) {
            if ($request->input('from') > $request->input('to')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Date is Invalid'
                ], Response::HTTP_BAD_REQUEST);
            } else {
                $rechargeBalances = RechargeBalance::where('shipping_point_id', '=', $request->input('shippingPoint'))->whereBetween('created_at', [$request->input('from'), $request->input('to')])->with('user')->with('shipping_point')->get();
                $total = RechargeBalance::where('shipping_point_id', '=', $request->input('shippingPoint'))->whereBetween('created_at', [$request->input('from'), $request->input('to')])->sum('balance');
                $pdf = PDF::loadView('pdf.shippingPointAdminTransaction', ['rechargeBalances' => $rechargeBalances, 'total' => $total]);
                return $pdf->download('report.pdf');
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
