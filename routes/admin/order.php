<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Order\OrderUpdateOrCreate;
use App\Livewire\Admin\Pages\Order\OrderTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/order', 'as' => 'admin.order.'], function () {
    Route::get('/', OrderTable::class)->name('index');
    Route::get('create', OrderUpdateOrCreate::class)->name('create')->can('create,App\Models\Order');
    Route::get('{order}/edit', OrderUpdateOrCreate::class)->name('edit')->can('update,order');
});
