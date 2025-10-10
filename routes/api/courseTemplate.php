<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CourseTemplateController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'course-template', 'as' => 'courseTemplate.'], function () {
    Route::get('/', [CourseTemplateController::class, 'index'])->name('index');
    Route::get('{courseTemplate:slug}', [CourseTemplateController::class, 'show'])->name('show');
});
