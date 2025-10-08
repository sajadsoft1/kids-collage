<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\CourseSession\CourseSessionTable;
use App\Livewire\Admin\Pages\CourseSession\CourseSessionUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/course-session', 'as' => 'admin.course-session.'], function () {
    Route::get('/', CourseSessionTable::class)->name('index');
    Route::get('create', CourseSessionUpdateOrCreate::class)->name('create')->can('create,App\Models\CourseSession');
    Route::get('{course-session}/edit', CourseSessionUpdateOrCreate::class)->name('edit')->can('update,course-session');
});
