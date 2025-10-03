<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BannerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'banner', 'as' => 'banner.'], function () {
    Route::get('/', [BannerController::class, 'index'])->name('index');
    Route::get('{banner}', [BannerController::class, 'show'])->name('show');
    Route::get('view-counter/{banner}', [BannerController::class, 'viewCounter'])->name('view-counter');
});

