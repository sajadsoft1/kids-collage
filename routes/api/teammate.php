<?php

declare(strict_types=1);

use App\Http\Controllers\Api\TeammateController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'teammate', 'as' => 'teammate.'], function () {
     Route::post('toggle/{teammate}', [TeammateController::class, 'toggle'])->name('toggle');
    // Route::get('data', [TeammateController::class, 'extraData'])->name('data');
});
Route::apiResource('teammate', TeammateController::class);
