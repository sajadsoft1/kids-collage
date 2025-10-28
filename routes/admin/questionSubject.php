<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\QuestionSubject\QuestionSubjectUpdateOrCreate;
use App\Livewire\Admin\Pages\QuestionSubject\QuestionSubjectTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/question-subject', 'as' => 'admin.question-subject.'], function () {
    Route::get('/', QuestionSubjectTable::class)->name('index');
    Route::get('create', QuestionSubjectUpdateOrCreate::class)->name('create')->can('create,App\Models\QuestionSubject');
    Route::get('{question-subject}/edit', QuestionSubjectUpdateOrCreate::class)->name('edit')->can('update,question-subject');
});
