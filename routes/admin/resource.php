<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Resource\ResourceTable;
use App\Livewire\Admin\Pages\Resource\ResourceUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/resource', 'as' => 'admin.resource.'], function () {
    Route::get('/', ResourceTable::class)->name('index');
    Route::get('create', ResourceUpdateOrCreate::class)->name('create')->can('create,App\Models\Resource');
    Route::get('{resource}/edit', ResourceUpdateOrCreate::class)->name('edit')->can('update,resource');
});
