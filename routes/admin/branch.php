<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Branch\BranchTable;
use App\Livewire\Admin\Pages\Branch\BranchUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/branch', 'as' => 'admin.branch.'], function () {
    Route::get('/', BranchTable::class)->name('index');
    Route::get('create', BranchUpdateOrCreate::class)->name('create')->can('create,App\Models\Branch');
    Route::get('{branch}/edit', BranchUpdateOrCreate::class)->name('edit')->can('update,branch');
});
