<?php

use App\Http\Controllers\Api\FaqController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'faq', 'as' => 'faq.'], function () {
    // Route::post('toggle/{faq}', [FaqController::class, 'toggle'])->name('toggle');
    // Route::get('data', [FaqController::class, 'extraData'])->name('data');
});
Route::apiResource('faq', FaqController::class);

