<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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
    });
