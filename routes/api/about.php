<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AboutUsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'about-us', 'as' => 'about.'], function () {
    Route::get('/', [AboutUsController::class, 'getAbout'])->name('about');
});
