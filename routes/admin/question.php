<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Question\QuestionTable;
use App\Livewire\Admin\Pages\Question\QuestionUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/question', 'as' => 'admin.question.'], function () {
    Route::get('/', QuestionTable::class)->name('index');
    Route::get('create', QuestionUpdateOrCreate::class)->name('create')->can('create,App\Models\Question');
    Route::get('{question}/edit', QuestionUpdateOrCreate::class)->name('edit')->can('update,question');
});
