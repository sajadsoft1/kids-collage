<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Client\ClientTable;
use App\Livewire\Admin\Pages\Client\ClientUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/client', 'as' => 'admin.client.'], function () {
    Route::get('/', ClientTable::class)->name('index');
    Route::get('create', ClientUpdateOrCreate::class)->name('create')->can('create,App\Models\Client');
    Route::get('{client}/edit', ClientUpdateOrCreate::class)->name('edit')->can('update,client');
});
