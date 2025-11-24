<?php

declare(strict_types=1);

use App\Http\Controllers\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'event', 'as' => 'event.'], function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    //    Route::get('category/{category:slug}', [EventController::class, 'indexByCategory'])->name('by-category');
    //    Route::get('data', [EventController::class, 'data'])->name('data');
    Route::get('{event:slug}', [EventController::class, 'show'])->name('show');
});
