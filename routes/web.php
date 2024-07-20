<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentCategoryController;
use App\Http\Controllers\PaymentProviderController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\RechargeBalanceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShippingPointController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('cms/')->middleware('guest:admin,shippingPoint')->group(function () {
    Route::get('{guard}/login', [AuthController::class, 'showLogin'])->name('auth.showLogin');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forget');
    Route::post('forgot-password', [AuthController::class, 'sendResetEmail'])->name('auth.send');
    Route::get('forgot-password/{token}', [AuthController::class, 'recoverPassword'])->name('password.reset');
    Route::put('forgot-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::prefix('cms/admin')->middleware(['auth:admin', 'verified'])->group(function () {
    Route::get('/admins/deleted', [AdminController::class, 'showDeleted'])->name('admins.deleted');
    Route::put('/admins/deleted/restore/{admin}', [AdminController::class, 'restore'])->name('admins.restore');
    Route::put('/admins/deleted/force-delete/{admin}', [AdminController::class, 'forceDelete'])->name('admins.forceDelete');
    Route::resource('/admins', AdminController::class);

    Route::get('/payment-categories/deleted', [PaymentCategoryController::class, 'showDeleted'])->name('paymentCategories.deleted');
    Route::put('/payment-categories/deleted/restore/{paymentCategory}', [PaymentCategoryController::class, 'restore'])->name('paymentCategories.restore');
    Route::put('/payment-categories/deleted/force-delete/{paymentCategory}', [PaymentCategoryController::class, 'forceDelete'])->name('paymentCategories.forceDelete');
    Route::resource('/payment-categories', PaymentCategoryController::class);
    Route::get('/payment-providers/deleted', [PaymentProviderController::class, 'showDeleted'])->name('paymentProviders.deleted');
    Route::put('/payment-providers/deleted/restore/{paymentProvider}', [PaymentProviderController::class, 'restore'])->name('paymentProviders.restore');
    Route::put('/payment-providers/deleted/force-delete/{paymentProvider}', [PaymentProviderController::class, 'forceDelete'])->name('paymentProviders.forceDelete');
    Route::resource('/payment-providers', PaymentProviderController::class);

    Route::resource('/roles', RoleController::class);
    Route::put('roles/permissions/edit', [RoleController::class, 'updateRolePermission'])->name('roles.updateRolePermission');

    Route::get('/transactions/all', [TransactionController::class, 'allTransactions'])->name('all.transaction');
    Route::post('/transactions/all/report', [PDFController::class, 'all'])->name('allReport.transaction');

    Route::get('/transactions/users', [TransactionController::class, 'userTransactions'])->name('user.transaction');
    Route::post('/transactions/user/report', [PDFController::class, 'user'])->name('userReport.transaction');

    Route::get('/transactions/payment-providers', [TransactionController::class, 'paymentProviderTransactions'])->name('paymentProvider.transaction');
    Route::post('/transactions/payment-provider/report', [PDFController::class, 'paymentProvider'])->name('paymentProviderReport.transaction');

    Route::get('/transactions/shipping-points/admin', [TransactionController::class, 'shippingPointTransactionsAdmin'])->name('shippingPointAdmin.transaction');
    Route::post('/transactions/shipping-point/report', [PDFController::class, 'shippingPoint'])->name('shippingPointAdminReport.transaction');


    Route::get('/shipping-points/deleted', [ShippingPointController::class, 'showDeleted'])->name('shippingPoints.deleted');
    Route::put('/shipping-points/deleted/restore/{paymentProvider}', [ShippingPointController::class, 'restore'])->name('shippingPoints.restore');
    Route::put('/shipping-points/deleted/force-delete/{paymentProvider}', [ShippingPointController::class, 'forceDelete'])->name('shippingPoints.forceDelete');
    Route::put('/shipping-points/frozen/{shippingPoint}', [ShippingPointController::class, 'frozen'])->name('shippingPoints.frozen');
    Route::resource('/shipping-points', ShippingPointController::class);
});

Route::prefix('cms/admin')->middleware(['auth:admin,shippingPoint', 'verified'])->group(function () {
    Route::get('', [HomeController::class, 'index'])->name('cms.index');
    Route::get('/edit-password', [AuthController::class, 'editPassword'])->name('auth.edit');
    Route::put('/edit-password', [AuthController::class, 'updatePassword'])->name('auth.update');
    Route::get('/profile/edit', [AuthController::class, 'editUser'])->name('auth.editUser');
    Route::put('/profile/edit', [AuthController::class, 'updateUser'])->name('auth.updateUser');
    Route::get('/notifications/readAll', [AuthController::class, 'readAllNotifications'])->name('readAllNotifications');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
Route::prefix('cms/admin')->middleware(['auth:shippingPoint', 'verified'])->group(function () {
    Route::get('/recharge', [RechargeBalanceController::class, 'index'])->name('recharge.index');
    Route::get('/recharge/search', [RechargeBalanceController::class, 'search'])->name('user.search');
    Route::get('/transactions/shipping-points', [TransactionController::class, 'shippingPointTransactions'])->name('shippingPoint.transaction');
    Route::get('/transactions/report', [PDFController::class, 'shippingPointTransactionDaily'])->name('shippingPointReport.transaction');
    Route::get('/recharge/search/{user}', [RechargeBalanceController::class, 'showShipping'])->name('user.showShipping');
    Route::post('/recharge/search/{user}', [RechargeBalanceController::class, 'shipping'])->name('user.shipping');
});

Route::prefix('')->middleware('auth:admin,shippingPoint')->group(function () {
    Route::get('/email-verification', [AuthController::class, 'showEmailVerification'])->name('verification.notice');
    Route::get('/email-verification/request', [AuthController::class, 'sendVerifyEmail'])->middleware('throttle:5,1')->name('verification.request');
    Route::get('/email-verification/{id}/{hash}', [AuthController::class, 'verify'])->middleware('signed')->name('verification.verify');
});
