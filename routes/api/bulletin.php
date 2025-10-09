<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BulletinController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'bulletin', 'as' => 'bulletin.'], function () {
    Route::get('/', [BulletinController::class, 'index'])->name('index');
    Route::get('/index-extra-data', [BulletinController::class, 'indexExtraData'])->name('index-extra-data');
    Route::get('{bulletin:slug}/show-data', [BulletinController::class, 'extraShowData'])->name('show-extra-data');
    Route::get('{bulletin:slug}', [BulletinController::class, 'show'])->name('show');
    Route::get('category/{category:slug}', [BulletinController::class, 'indexByCategory'])->name('by-category');
    Route::get('tag/{tag:slug}', [BulletinController::class, 'indexByTag'])->name('by-tag');
    Route::get('author/{user}', [BulletinController::class, 'indexByUser'])->name('by-user');
});
