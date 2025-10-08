<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\CourseTemplate\CourseTemplateIndex;
use App\Livewire\Admin\Pages\CourseTemplate\CourseTemplateUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/course-template', 'as' => 'admin.course-template.'], function () {
    Route::get('/', CourseTemplateIndex::class)->name('index');
    Route::get('create', CourseTemplateUpdateOrCreate::class)->name('create')->can('create,App\Models\CourseTemplate');
    Route::get('{courseTemplate}/edit', CourseTemplateUpdateOrCreate::class)->name('edit')->can('update,courseTemplate');
});
