<?php

declare(strict_types=1);

use App\Http\Controllers\Api\RuleController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'rules', 'as' => 'rules.'], function () {
    Route::get('/', [RuleController::class, 'getRules'])->name('rules');
});
