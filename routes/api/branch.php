<?php

use App\Http\Controllers\Api\BranchController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'branch', 'as' => 'branch.'], function () {
     Route::post('toggle/{branch}', [BranchController::class, 'toggle'])->name('toggle');
    // Route::get('data', [BranchController::class, 'extraData'])->name('data');
});
Route::apiResource('branch', BranchController::class);

