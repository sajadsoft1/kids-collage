<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\CourseSessionTemplate\CourseSessionTemplateTable;
use App\Livewire\Admin\Pages\CourseSessionTemplate\CourseSessionTemplateUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/course-template/session', 'as' => 'admin.course-session-template.'], function () {
    Route::get('{courseTemplate}/', CourseSessionTemplateTable::class)->name('index');
    Route::get('{courseTemplate}/create', CourseSessionTemplateUpdateOrCreate::class)->name('create')->can('create,App\Models\CourseSessionTemplate');
    Route::get('{courseTemplate}/{courseSessionTemplate}/edit', CourseSessionTemplateUpdateOrCreate::class)->name('edit')->can('update,courseSessionTemplate');
});
