<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\QuestionCompetency\QuestionCompetencyTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/question-competency', 'as' => 'admin.question-competency.'], function () {
    Route::get('/', QuestionCompetencyTable::class)->name('index');
});
