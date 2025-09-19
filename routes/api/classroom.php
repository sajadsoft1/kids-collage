<?php

use App\Http\Controllers\Api\ClassroomController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'classroom', 'as' => 'classroom.'], function () {
     Route::post('toggle/{classroom}', [ClassroomController::class, 'toggle'])->name('toggle');
    // Route::get('data', [ClassroomController::class, 'extraData'])->name('data');
});
Route::apiResource('classroom', ClassroomController::class);

