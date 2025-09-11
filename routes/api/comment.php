<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CommentController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'comment', 'as' => 'comment.'], function () {
    // Route::post('toggle/{comment}', [CommentController::class, 'toggle'])->name('toggle');
    // Route::get('data', [CommentController::class, 'extraData'])->name('data');
});
Route::apiResource('comment', CommentController::class);
