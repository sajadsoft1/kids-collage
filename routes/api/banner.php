<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BannerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'banner', 'as' => 'banner.'], function () {
     Route::post('toggle/{banner}', [BannerController::class, 'toggle'])->name('toggle');
     Route::get('data', [BannerController::class, 'extraData'])->name('data');
});
Route::apiResource('banner', BannerController::class);
