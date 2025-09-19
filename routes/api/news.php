<?php

use App\Http\Controllers\Api\NewsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'news', 'as' => 'news.'], function () {
     Route::post('toggle/{news}', [NewsController::class, 'toggle'])->name('toggle');
     Route::get('data', [NewsController::class, 'extraData'])->name('data');
});
Route::apiResource('news', NewsController::class);

