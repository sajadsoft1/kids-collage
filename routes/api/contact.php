<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ContactUsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'contact-us', 'as' => 'contact.'], function () {
    Route::post('/', [ContactUsController::class, 'store'])->name('store');
});
