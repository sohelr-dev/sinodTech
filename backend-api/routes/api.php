<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\BranchController;

//Auth routes guest only
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    //forget password reset routes
    Route::post('forgot-password', [PasswordResetController::class, 'sendResetRequest']);
    Route::post('verify-code', [PasswordResetController::class, 'Verify']);
    Route::post('reset-password', [PasswordResetController::class, 'reset']);
});

//Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('all-users', [AuthController::class, 'getNewRegisterUsers']);
    Route::post('user/register', [AuthController::class, 'roleRegister']);

    // ─── Product Management ───────────────────────────────────────────
    Route::apiResource('products', ProductController::class);

    // ─── Sales & Checkout ─────────────────────────────────────────────
    Route::get('sales', [SaleController::class, 'index']);
    Route::post('sales', [SaleController::class, 'store']);
    Route::get('sales/{id}', [SaleController::class, 'show']);

    // ─── Branches & Customers ─────────────────────────────────────────
    Route::get('branches', [BranchController::class, 'index']);
    Route::get('customers', [CustomerController::class, 'index']);
});
