<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\QuestionSystem\QuestionSystemTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/question-system', 'as' => 'admin.question-system.'], function () {
    Route::get('/', QuestionSystemTable::class)->name('index');
});
