<?php

declare(strict_types=1);

use App\Http\Controllers\Api\SocialMediaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'social-media', 'as' => 'socialMedia.'], function () {
    Route::get('/actives', [SocialMediaController::class, 'actives'])->name('actives');
});
