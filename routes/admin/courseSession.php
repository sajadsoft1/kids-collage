<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\CourseSession\CourseSessionTable;
use App\Livewire\Admin\Pages\CourseSession\CourseSessionUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/course-template/course/session', 'as' => 'admin.course-session.'], function () {
    Route::get('{courseTemplate}/{course}/', CourseSessionTable::class)->name('index');
    Route::get('{courseTemplate}/{course}/create', CourseSessionUpdateOrCreate::class)->name('create')->can('create,App\Models\CourseSession');
    Route::get('{courseTemplate}/{course}/{courseSession}/edit', CourseSessionUpdateOrCreate::class)->name('edit')->can('update,courseSession');
});
