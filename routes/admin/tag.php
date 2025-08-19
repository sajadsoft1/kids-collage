<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Tag\TagTable;
use App\Livewire\Admin\Pages\Tag\TagUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/tag', 'as' => 'admin.tag.'], function () {
    Route::get('/', TagTable::class)->name('index');
    Route::get('create', TagUpdateOrCreate::class)->name('create')->can('create,App\Models\Tag');
    Route::get('{tag}/edit', TagUpdateOrCreate::class)->name('edit')->can('update,tag');
});
