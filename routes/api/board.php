<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BoardController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'board', 'as' => 'board.'], function () {
    // Route::post('toggle/{board}', [BoardController::class, 'toggle'])->name('toggle');
    // Route::get('data', [BoardController::class, 'extraData'])->name('data');
});
Route::apiResource('board', BoardController::class);
