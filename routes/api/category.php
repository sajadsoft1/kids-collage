<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
    // Route::post('toggle/{category}', [CategoryController::class, 'toggle'])->name('toggle');
    // Route::get('data', [CategoryController::class, 'extraData'])->name('data');
});
Route::apiResource('category', CategoryController::class);
