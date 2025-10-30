<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\QuestionOption\QuestionOptionTable;
use App\Livewire\Admin\Pages\QuestionOption\QuestionOptionUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/question-option', 'as' => 'admin.question-option.'], function () {
    Route::get('/', QuestionOptionTable::class)->name('index');
    Route::get('create', QuestionOptionUpdateOrCreate::class)->name('create')->can('create,App\Models\QuestionOption');
    Route::get('{question-option}/edit', QuestionOptionUpdateOrCreate::class)->name('edit')->can('update,question-option');
});
