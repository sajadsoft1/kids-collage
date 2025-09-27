<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ClientController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
    Route::get('/', [ClientController::class, 'index'])->name('index');
    Route::get('{client}', [ClientController::class, 'show'])->name('show');
});
