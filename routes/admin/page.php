<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Page\PageTable;
use App\Livewire\Admin\Pages\Page\PageUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/page', 'as' => 'admin.page.'], function () {
    Route::get('/', PageTable::class)->name('index');
    Route::get('create', PageUpdateOrCreate::class)->name('create')->can('create,App\Models\Page');
    Route::get('{page}/edit', PageUpdateOrCreate::class)->name('edit')->can('update,page');
});
