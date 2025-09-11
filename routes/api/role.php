<?php

declare(strict_types=1);

use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
    // Route::post('toggle/{role}', [RoleController::class, 'toggle'])->name('toggle');
    // Route::get('data', [RoleController::class, 'extraData'])->name('data');
});
Route::apiResource('role', RoleController::class);
