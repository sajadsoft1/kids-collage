<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Exam\ExamAttemptTable;
use App\Livewire\Admin\Pages\Exam\ExamListForUsers;
use App\Livewire\Admin\Pages\Exam\ExamResults;
use App\Livewire\Admin\Pages\Exam\ExamTable;
use App\Livewire\Admin\Pages\Exam\ExamTaker;
use App\Livewire\Admin\Pages\Exam\ExamUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/exam', 'as' => 'admin.exam.'], function () {
    Route::get('/', ExamTable::class)->name('index');
    Route::get('list', ExamListForUsers::class)->name('list');
    Route::get('create', ExamUpdateOrCreate::class)->name('create')->can('create,App\Models\Exam');
    Route::get('{exam}/edit', ExamUpdateOrCreate::class)->name('edit')->can('update,exam');
    Route::get('{exam}/taker/{attempt?}', ExamTaker::class)->name('taker');
    Route::get('{exam}/attempt/{attempt}/review', ExamTaker::class)->name('attempt.review');

    // ExamAttempt routes
    Route::get('{exam}/attempts', ExamAttemptTable::class)->name('attempt.index');
    Route::get('{exam}/attempt/{attempt}/results', ExamResults::class)->name('attempt.results');
});
