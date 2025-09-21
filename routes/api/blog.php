<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BlogController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
    Route::post('toggle/{blog}', [BlogController::class, 'toggle'])->name('toggle');
    Route::get('data', [BlogController::class, 'extraData'])->name('data');
});
Route::apiResource('blog', BlogController::class);
