<?php

use App\Http\Controllers\Api\ClientController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
    // Route::post('toggle/{client}', [ClientController::class, 'toggle'])->name('toggle');
    // Route::get('data', [ClientController::class, 'extraData'])->name('data');
});
Route::apiResource('client', ClientController::class);

