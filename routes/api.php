<?php

use App\Http\Controllers\auth\AuthApiController;
use App\Http\Controllers\MoneyTransferController;
use App\Http\Controllers\PaymentCategoryController;
use App\Http\Controllers\PaymentOfBillController;
use App\Http\Controllers\PaymentProviderController;
use App\Http\Controllers\PaymentTransactionController;
use App\Http\Controllers\ShippingTransactionController;
use App\Http\Controllers\TransferTransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('forgot-password', [AuthApiController::class, 'sendResetEmail'])->name('auth.send');
    Route::put('forgot-password', [AuthApiController::class, 'resetPassword'])->name('password.update');
});

Route::prefix('cms/user')->middleware(['auth:user-api', 'verified'])->group(function () {
    Route::post('/money-transfer', [MoneyTransferController::class, 'moneyTransfer']);
    Route::post('/payment-of-bills', [PaymentOfBillController::class, 'paymentOfBills']);
    Route::put('/change-password', [AuthApiController::class, 'changePassword']);
    Route::put('/update-profile', [AuthApiController::class, 'updateProfile']);
    Route::get('/payment-transactions', [PaymentTransactionController::class, 'paymentTransactions']);
    Route::get('/shipping-transactions', [ShippingTransactionController::class, 'shippingTransactions']);
    Route::get('/transfer-transactions', [TransferTransactionController::class, 'transferTransactions']);
    Route::get('/payment-categories', [PaymentCategoryController::class, 'index']);
    Route::get('/payment-providers', [PaymentProviderController::class, 'index']);
    Route::get('/logout', [AuthApiController::class, 'logout']);
});

Route::prefix('verify')->middleware('auth:user-api')->group(function () {
    Route::post('/email-verification', [AuthApiController::class, 'sendVerifyEmail'])->middleware('throttle:5,1')->name('verification.request');
    Route::get('/email-verification/{id}/{hash}', [AuthApiController::class, 'verify'])->name('verification.verify');
});
