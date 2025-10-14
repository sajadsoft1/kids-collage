<?php

declare(strict_types=1);

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user', 'as' => 'user.'], function () {});
Route::get('teachers', [UserController::class, 'teachers'])->name('teachers');
Route::get('teacher/{user}', [UserController::class, 'showTeacher'])->name('teacher-show');
// Route::apiResource('user', UserController::class);
