<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('confirm-otp', [AuthController::class, 'confirmOtp'])->name('confirmOtp');
    Route::post('forget-password', [AuthController::class, 'forgetPassword'])->name('forgetPassword');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('me', [AuthController::class, 'me'])->name('me');
});
