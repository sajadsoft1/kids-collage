<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'home', 'as' => 'home.'], function () {
    Route::get('/', [App\Http\Controllers\Api\HomeController::class, 'index'])->name('index');
});
