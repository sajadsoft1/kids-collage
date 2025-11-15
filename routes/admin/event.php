<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Event\EventUpdateOrCreate;
use App\Livewire\Admin\Pages\Event\EventTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/event', 'as' => 'admin.event.'], function () {
    Route::get('/', EventTable::class)->name('index');
    Route::get('create', EventUpdateOrCreate::class)->name('create')->can('create,App\Models\Event');
    Route::get('{event}/edit', EventUpdateOrCreate::class)->name('edit')->can('update,event');
});
