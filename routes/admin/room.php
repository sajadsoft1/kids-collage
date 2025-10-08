<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Room\RoomTable;
use App\Livewire\Admin\Pages\Room\RoomUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/room', 'as' => 'admin.room.'], function () {
    Route::get('/', RoomTable::class)->name('index');
    Route::get('create', RoomUpdateOrCreate::class)->name('create')->can('create,App\Models\Room');
    Route::get('{room}/edit', RoomUpdateOrCreate::class)->name('edit')->can('update,room');
});
