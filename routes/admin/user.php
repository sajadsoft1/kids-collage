<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\User\EmployeeIndex;
use App\Livewire\Admin\Pages\User\ParentIndex;
use App\Livewire\Admin\Pages\User\UserIndex;
use App\Livewire\Admin\Pages\User\UserUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/user', 'as' => 'admin.user.'], function () {
    Route::get('/', UserIndex::class)->name('index');
    Route::get('create', UserUpdateOrCreate::class)->name('create');
    Route::get('{user}/edit', UserUpdateOrCreate::class)->name('edit');
});

Route::group(['prefix' => 'admin/parent', 'as' => 'admin.parent.'], function () {
    Route::get('/', ParentIndex::class)->name('index');
    Route::get('create', UserUpdateOrCreate::class)->name('create');
    Route::get('{user}/edit', UserUpdateOrCreate::class)->name('edit');
});

Route::group(['prefix' => 'admin/employee', 'as' => 'admin.employee.'], function () {
    Route::get('/', EmployeeIndex::class)->name('index');
    Route::get('create', UserUpdateOrCreate::class)->name('create');
    Route::get('{user}/edit', UserUpdateOrCreate::class)->name('edit');
});
