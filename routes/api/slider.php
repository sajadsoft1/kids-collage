<?php

declare(strict_types=1);

use App\Http\Controllers\Api\SliderController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'slider', 'as' => 'slider.'], function () {
     Route::post('toggle/{slider}', [SliderController::class, 'toggle'])->name('toggle');
     Route::get('data', [SliderController::class, 'extraData'])->name('data');
});
Route::apiResource('slider', SliderController::class);
