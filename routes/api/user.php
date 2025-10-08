<?php

declare(strict_types=1);

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user', 'as' => 'user.'], function () {});
Route::get('teachers', [UserController::class, 'teachers'])->name('teachers');
// Route::apiResource('user', UserController::class);
