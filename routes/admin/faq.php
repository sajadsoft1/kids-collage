<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Faq\FaqTable;
use App\Livewire\Admin\Pages\Faq\FaqUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/faq', 'as' => 'admin.faq.'], function () {
    Route::get('/', FaqTable::class)->name('index');
    Route::get('create', FaqUpdateOrCreate::class)->name('create')->can('create,App\Models\Faq');
    Route::get('{faq}/edit', FaqUpdateOrCreate::class)->name('edit')->can('update,faq');
});
