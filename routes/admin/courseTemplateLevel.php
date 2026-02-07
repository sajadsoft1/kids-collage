<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\CourseTemplateLevel\CourseTemplateLevelTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/course-template-level', 'as' => 'admin.course-template-level.'], function () {
    Route::get('/', CourseTemplateLevelTable::class)->name('index');
});
