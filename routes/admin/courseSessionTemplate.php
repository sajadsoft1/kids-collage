<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\CourseSessionTemplate\CourseSessionTemplateUpdateOrCreate;
use App\Livewire\Admin\Pages\CourseSessionTemplate\CourseSessionTemplateTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/course-session-template', 'as' => 'admin.course-session-template.'], function () {
    Route::get('/', CourseSessionTemplateTable::class)->name('index');
    Route::get('create', CourseSessionTemplateUpdateOrCreate::class)->name('create')->can('create,App\Models\CourseSessionTemplate');
    Route::get('{course-session-template}/edit', CourseSessionTemplateUpdateOrCreate::class)->name('edit')->can('update,course-session-template');
});
