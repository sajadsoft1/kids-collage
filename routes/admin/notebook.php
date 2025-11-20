<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Notebook\NotebookUpdateOrCreate;
use App\Livewire\Admin\Pages\Notebook\NotebookTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/notebook', 'as' => 'admin.notebook.'], function () {
    Route::get('/', NotebookTable::class)->name('index');
    Route::get('create', NotebookUpdateOrCreate::class)->name('create')->can('create,App\Models\Notebook');
    Route::get('{notebook}/edit', NotebookUpdateOrCreate::class)->name('edit')->can('update,notebook');
});
