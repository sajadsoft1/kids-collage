<?php

declare(strict_types=1);

use App\Http\Controllers\Api\FlashCardController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'flash-card', 'as' => 'flash-card.'], function () {
    Route::get('/', [FlashCardController::class, 'index'])->name('index');
    Route::get('{flashCard}', [FlashCardController::class, 'show'])->name('show');
});
