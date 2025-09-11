<?php

declare(strict_types=1);

use App\Http\Controllers\Api\PageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'page', 'as' => 'page.'], function () {
    // Route::post('toggle/{page}', [PageController::class, 'toggle'])->name('toggle');
    // Route::get('data', [PageController::class, 'extraData'])->name('data');
});
Route::apiResource('page', PageController::class);
