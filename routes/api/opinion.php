<?php

use App\Http\Controllers\Api\OpinionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'opinion', 'as' => 'opinion.'], function () {
    // Route::post('toggle/{opinion}', [OpinionController::class, 'toggle'])->name('toggle');
    // Route::get('data', [OpinionController::class, 'extraData'])->name('data');
});
Route::apiResource('opinion', OpinionController::class);

