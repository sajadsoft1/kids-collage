<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Course\CourseUpdateOrCreate;
use App\Livewire\Admin\Pages\Course\CourseTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/course', 'as' => 'admin.course.'], function () {
    Route::get('/', CourseTable::class)->name('index');
    Route::get('create', CourseUpdateOrCreate::class)->name('create')->can('create,App\Models\Course');
    Route::get('{course}/edit', CourseUpdateOrCreate::class)->name('edit')->can('update,course');
});
