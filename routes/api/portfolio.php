<?php

use App\Http\Controllers\Api\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'portfolio', 'as' => 'portfolio.'], function () {
    // Route::post('toggle/{portfolio}', [PortfolioController::class, 'toggle'])->name('toggle');
    // Route::get('data', [PortfolioController::class, 'extraData'])->name('data');
});
Route::apiResource('portfolio', PortfolioController::class);

