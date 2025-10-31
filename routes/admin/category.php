<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Category\CategoryIndex;
use App\Livewire\Admin\Pages\Category\CategoryUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/category', 'as' => 'admin.category.'], function () {
    Route::get('/', CategoryIndex::class)->name('index');
    Route::get('create', CategoryUpdateOrCreate::class)->name('create')->can('create,App\Models\Category');
    Route::get('{category}/edit', CategoryUpdateOrCreate::class)->name('edit')->can('update,category');
});
