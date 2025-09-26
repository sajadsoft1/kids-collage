<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Installment\InstallmentUpdateOrCreate;
use App\Livewire\Admin\Pages\Installment\InstallmentTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/installment', 'as' => 'admin.installment.'], function () {
    Route::get('/', InstallmentTable::class)->name('index');
    Route::get('create', InstallmentUpdateOrCreate::class)->name('create')->can('create,App\Models\Installment');
    Route::get('{installment}/edit', InstallmentUpdateOrCreate::class)->name('edit')->can('update,installment');
});
