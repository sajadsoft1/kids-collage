<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Exam\ExamTable;
use App\Livewire\Admin\Pages\Exam\ExamUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/exam', 'as' => 'admin.exam.'], function () {
    Route::get('/', ExamTable::class)->name('index');
    Route::get('create', ExamUpdateOrCreate::class)->name('create')->can('create,App\Models\Exam');
    Route::get('{exam}/edit', ExamUpdateOrCreate::class)->name('edit')->can('update,exam');
});
