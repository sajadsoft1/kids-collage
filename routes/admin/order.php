<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Order\OrderCourseDetail;
use App\Livewire\Admin\Pages\Order\OrderCourseTable;
use App\Livewire\Admin\Pages\Order\OrderCourseUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/order', 'as' => 'admin.order.'], function () {
    Route::get('/', OrderCourseTable::class)->name('index');
    Route::get('/course/create', OrderCourseUpdateOrCreate::class)->name('create')->can('create,App\Models\Order');
    Route::get('/course/{order}/edit', OrderCourseUpdateOrCreate::class)->name('edit')->can('update,order');
    Route::get('/course/{order}', OrderCourseDetail::class)->name('show')->can('view,order');
});
