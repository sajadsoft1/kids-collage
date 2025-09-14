<?php

declare(strict_types=1);

use App\Http\Controllers\Api\SocialMediaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'social-media', 'as' => 'social-media.'], function () {
     Route::post('toggle/{socialMedia}', [SocialMediaController::class, 'toggle'])->name('toggle');
     Route::get('data', [SocialMediaController::class, 'extraData'])->name('data');
});
Route::apiResource('social-media', SocialMediaController::class);
