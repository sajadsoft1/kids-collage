<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BulletinController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'bulletin', 'as' => 'bulletin.'], function () {
    Route::get('/', [BulletinController::class, 'index'])->name('index');
    Route::get('{bulletin:slug}', [BulletinController::class, 'show'])->name('show');
    Route::get('category/{category:slug}', [BulletinController::class, 'indexByCategory'])->name('by-category');
    Route::get('tag/{tag:slug}', [BulletinController::class, 'indexByTag'])->name('by-tag');
    Route::get('author/{user}', [BulletinController::class, 'indexByUser'])->name('by-user');
    Route::get('{bulletin:slug}/data', [BulletinController::class, 'extraData'])->name('extra-data');
});
