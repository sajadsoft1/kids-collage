<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Payment\PaymentSingle;
use App\Livewire\Admin\Pages\Payment\PaymentTable;
use App\Livewire\Admin\Pages\Payment\PaymentUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/payment', 'as' => 'admin.payment.'], function () {
    Route::get('/', PaymentTable::class)->name('index');
    Route::get('create', PaymentUpdateOrCreate::class)->name('create')->can('create,App\Models\Payment');
    Route::get('{payment}/edit', PaymentUpdateOrCreate::class)->name('edit')->can('update,payment');
    Route::get('{payment}', PaymentSingle::class)->name('show');
});
