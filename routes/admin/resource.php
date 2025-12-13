<?php

declare(strict_types=1);

use App\Http\Controllers\ResourceAccessController;
use App\Livewire\Admin\Pages\Resource\ResourceTable;
use App\Livewire\Admin\Pages\Resource\ResourceUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/resource', 'as' => 'admin.resource.'], function () {
    Route::get('/', ResourceTable::class)->name('index');
    Route::get('create', ResourceUpdateOrCreate::class)->name('create')->can('create,App\Models\Resource');
    Route::get('{resource}/edit', ResourceUpdateOrCreate::class)->name('edit')->can('update,resource');
});

// Resource download route (for authenticated users: students, teachers, parents)
Route::middleware('auth')->group(function () {
    Route::get('resources/{resource}/download', [ResourceAccessController::class, 'download'])
        ->name('resources.download');
});
