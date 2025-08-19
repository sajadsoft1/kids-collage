<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Role\RoleApp;
use App\Livewire\Admin\Pages\Role\RoleUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/role', 'as' => 'admin.role.'], function () {
    Route::get('/', RoleApp::class)->name('index');
    Route::get('/edit-create/{role?}', RoleUpdateOrCreate::class)->name('edit-create');
    Route::get('/show', RoleApp::class)->name('show');
});
