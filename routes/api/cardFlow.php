<?php

use App\Http\Controllers\Api\CardFlowController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'card-flow', 'as' => 'card-flow.'], function () {
    // Route::post('toggle/{cardFlow}', [CardFlowController::class, 'toggle'])->name('toggle');
    // Route::get('data', [CardFlowController::class, 'extraData'])->name('data');
});
Route::apiResource('card-flow', CardFlowController::class);

