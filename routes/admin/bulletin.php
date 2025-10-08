<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Bulletin\BulletinTable;
use App\Livewire\Admin\Pages\Bulletin\BulletinUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/bulletin', 'as' => 'admin.bulletin.'], function () {
    Route::get('/', BulletinTable::class)->name('index');
    Route::get('create', BulletinUpdateOrCreate::class)->name('create')->can('create,App\Models\Bulletin');
    Route::get('{bulletin}/edit', BulletinUpdateOrCreate::class)->name('edit')->can('update,bulletin');
});
