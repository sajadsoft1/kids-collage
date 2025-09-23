<?php

declare(strict_types=1);

use App\Http\Controllers\Api\FaqController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'faq', 'as' => 'faq.'], function () {
    Route::get('/', [FaqController::class, 'index'])->name('index');
    Route::get('{faq}', [FaqController::class, 'show'])->name('show');
    Route::get('category/{category:slug}', [FaqController::class, 'indexByCategory'])->name('by-category');
});
