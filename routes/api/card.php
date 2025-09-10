<?php

use App\Http\Controllers\Api\CardController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'card', 'as' => 'card.'], function () {
    // Route::post('toggle/{card}', [CardController::class, 'toggle'])->name('toggle');
    // Route::get('data', [CardController::class, 'extraData'])->name('data');
});
Route::apiResource('card', CardController::class);

