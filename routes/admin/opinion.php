<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Opinion\OpinionTable;
use App\Livewire\Admin\Pages\Opinion\OpinionUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/opinion', 'as' => 'admin.opinion.'], function () {
    Route::get('/', OpinionTable::class)->name('index');
    Route::get('create', OpinionUpdateOrCreate::class)->name('create')->can('create,App\Models\Opinion');
    Route::get('{opinion}/edit', OpinionUpdateOrCreate::class)->name('edit')->can('update,opinion');
});
