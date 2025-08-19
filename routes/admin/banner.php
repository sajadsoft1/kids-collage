<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Banner\BannerTable;
use App\Livewire\Admin\Pages\Banner\BannerUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/banner', 'as' => 'admin.banner.'], function () {
    Route::get('/', BannerTable::class)->name('index');
    Route::get('create', BannerUpdateOrCreate::class)->name('create')->can('create,App\Models\Banner');
    Route::get('{banner}/edit', BannerUpdateOrCreate::class)->name('edit')->can('update,banner');
});
