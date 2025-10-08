<?php

declare(strict_types=1);

use App\Http\Controllers\Api\LicenseController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'license', 'as' => 'license.'], function () {
    Route::get('/', [LicenseController::class, 'index'])->name('index');
    Route::get('{license:slug}', [LicenseController::class, 'show'])->name('show');
});
