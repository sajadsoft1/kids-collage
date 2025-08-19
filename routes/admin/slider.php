<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Slider\SliderTable;
use App\Livewire\Admin\Pages\Slider\SliderUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/slider', 'as' => 'admin.slider.'], function () {
    Route::get('/', SliderTable::class)->name('index');
    Route::get('create', SliderUpdateOrCreate::class)->name('create')->can('create,App\Models\Slider');
    Route::get('{slider}/edit', SliderUpdateOrCreate::class)->name('edit')->can('update,slider');
});
