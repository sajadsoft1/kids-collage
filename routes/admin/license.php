<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\License\LicenseTable;
use App\Livewire\Admin\Pages\License\LicenseUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/license', 'as' => 'admin.license.'], function () {
    Route::get('/', LicenseTable::class)->name('index');
    Route::get('create', LicenseUpdateOrCreate::class)->name('create')->can('create,App\Models\License');
    Route::get('{license}/edit', LicenseUpdateOrCreate::class)->name('edit')->can('update,license');
});
