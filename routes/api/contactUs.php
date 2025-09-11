<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ContactUsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'contact-us', 'as' => 'contact-us.'], function () {
    // Route::post('toggle/{contactUs}', [ContactUsController::class, 'toggle'])->name('toggle');
    // Route::get('data', [ContactUsController::class, 'extraData'])->name('data');
});
Route::apiResource('contact-us', ContactUsController::class);
