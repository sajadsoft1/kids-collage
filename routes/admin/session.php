<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Session\SessionTable;
use App\Livewire\Admin\Pages\Session\SessionUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/session', 'as' => 'admin.session.'], function () {
    Route::get('{course}/', SessionTable::class)->name('index');
    Route::get('create/{course}', SessionUpdateOrCreate::class)->name('create')->can('create,App\Models\Session');
    Route::get('{session}/edit', SessionUpdateOrCreate::class)->name('edit')->can('update,session');
});
