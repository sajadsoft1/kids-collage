<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Survey\SurveyList;
use App\Livewire\Admin\Pages\Survey\SurveyResults;
use App\Livewire\Admin\Pages\Survey\SurveyUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/survey', 'as' => 'admin.survey.'], function () {
    Route::get('/', SurveyList::class)->name('index');
    Route::get('create', SurveyUpdateOrCreate::class)->name('create');
    Route::get('{exam}/edit', SurveyUpdateOrCreate::class)->name('edit');
    Route::get('{exam}/results', SurveyResults::class)->name('results');
});
