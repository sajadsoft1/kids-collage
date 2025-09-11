<?php

declare(strict_types=1);

use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tag', 'as' => 'tag.'], function () {
    // Route::post('toggle/{tag}', [TagController::class, 'toggle'])->name('toggle');
    // Route::get('data', [TagController::class, 'extraData'])->name('data');
});
Route::apiResource('tag', TagController::class);
