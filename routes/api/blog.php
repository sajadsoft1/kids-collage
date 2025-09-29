<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BlogController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('{blog:slug}', [BlogController::class, 'show'])->name('show');
    Route::get('category/{category:slug}', [BlogController::class, 'indexByCategory'])->name('by-category');
    Route::get('tag/{tag:slug}', [BlogController::class, 'indexByTag'])->name('by-tag');
    Route::get('author/{user}', [BlogController::class, 'indexByUser'])->name('by-user');
    Route::get('{blog:slug}/data', [BlogController::class, 'extraData'])->name('extra-data');
});
