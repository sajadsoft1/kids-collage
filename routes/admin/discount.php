<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Discount\DiscountUpdateOrCreate;
use App\Livewire\Admin\Pages\Discount\DiscountTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/discount', 'as' => 'admin.discount.'], function () {
    Route::get('/', DiscountTable::class)->name('index');
    Route::get('create', DiscountUpdateOrCreate::class)->name('create')->can('create,App\Models\Discount');
    Route::get('{discount}/edit', DiscountUpdateOrCreate::class)->name('edit')->can('update,discount');
});
