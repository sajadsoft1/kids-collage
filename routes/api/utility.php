<?php

declare(strict_types=1);

use App\Http\Controllers\Api\SplashController;
use App\Http\Controllers\Api\UtilityController;
use App\Http\Middleware\CheckLicenceMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('splash', [SplashController::class, 'splash'])->name('splash')->withoutMiddleware(CheckLicenceMiddleware::class);

Route::group(['prefix' => 'utility', 'as' => 'utility.'], function () {
    Route::get('select/{model}', [UtilityController::class, 'select'])->name('select');
    Route::post('active', [UtilityController::class, 'activeClub'])->name('active')->withoutMiddleware(CheckLicenceMiddleware::class);
    Route::post('update', [UtilityController::class, 'update'])->name('update');
});
