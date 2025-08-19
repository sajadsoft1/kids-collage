<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\User\UserIndex;
use App\Livewire\Admin\Pages\User\UserUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/user', 'as' => 'admin.user.'], function () {
    Route::get('/', UserIndex::class)->name('index');
    Route::get('create', UserUpdateOrCreate::class)->name('create');
    Route::get('{user}/edit', UserUpdateOrCreate::class)->name('edit');
});
