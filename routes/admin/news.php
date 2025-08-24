<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\News\NewsUpdateOrCreate;
use App\Livewire\Admin\Pages\News\NewsTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/news', 'as' => 'admin.news.'], function () {
    Route::get('/', NewsTable::class)->name('index');
    Route::get('create', NewsUpdateOrCreate::class)->name('create')->can('create,App\Models\News');
    Route::get('{news}/edit', NewsUpdateOrCreate::class)->name('edit')->can('update,news');
});
