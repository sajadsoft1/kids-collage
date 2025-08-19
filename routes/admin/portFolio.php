<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\PortFolio\PortFolioTable;
use App\Livewire\Admin\Pages\PortFolio\PortFolioUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/portFolio', 'as' => 'admin.portFolio.'], function () {
    Route::get('/', PortFolioTable::class)->name('index');
    Route::get('create', PortFolioUpdateOrCreate::class)->name('create')->can('create,App\Models\PortFolio');
    Route::get('{portFolio}/edit', PortFolioUpdateOrCreate::class)->name('edit')->can('update,portFolio');
});
