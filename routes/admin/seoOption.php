<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\SeoOption\SeoOptionTable;
use App\Livewire\Admin\Pages\SeoOption\SeoOptionUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/seo-option', 'as' => 'admin.seo-option.'], function () {
    Route::get('/', SeoOptionTable::class)->name('index');
    Route::get('create', SeoOptionUpdateOrCreate::class)->name('create')->middleware('can:create,App\Models\SeoOption');
    Route::get('{seo-option}/edit', SeoOptionUpdateOrCreate::class)->name('edit')->middleware('can:update,{seo-option}');
});
