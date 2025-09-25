<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BannerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'faq', 'as' => 'faq.'], function () {
    Route::get('/', [BannerController::class, 'index'])->name('index');
    Route::get('{faq}', [BannerController::class, 'show'])->name('show');
    Route::get('category/{category:slug}', [BannerController::class, 'indexByCategory'])->name('by-category');
});
