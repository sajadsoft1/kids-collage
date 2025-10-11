<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Course\CourseDetail;
use App\Livewire\Admin\Pages\Course\CourseRuner;
use App\Livewire\Admin\Pages\Course\CourseTable;
use App\Livewire\Admin\Pages\Course\CourseUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/course', 'as' => 'admin.course.'], function () {
    Route::get('/{courseTemplate}', CourseTable::class)->name('index');
//    Route::get('create', CourseUpdateOrCreate::class)->name('create')->can('create,App\Models\Course');
    Route::get('{course}/edit', CourseUpdateOrCreate::class)->name('edit')->can('update,course');
    Route::get('{courseTemplate}/run', CourseRuner::class)->name('run');
    Route::get('{course}/show', CourseDetail::class)->name('show');
});
