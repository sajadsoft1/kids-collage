<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Teammate\TeammateTable;
use App\Livewire\Admin\Pages\Teammate\TeammateUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/teammate', 'as' => 'admin.teammate.'], function () {
    Route::get('/', TeammateTable::class)->name('index');
    Route::get('create', TeammateUpdateOrCreate::class)->name('create')->can('create,App\Models\Teammate');
    Route::get('{teammate}/edit', TeammateUpdateOrCreate::class)->name('edit')->can('update,teammate');
});
