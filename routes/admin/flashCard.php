<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\FlashCard\FlashCardTable;
use App\Livewire\Admin\Pages\FlashCard\FlashCardUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/flash-card', 'as' => 'admin.flash-card.'], function () {
    Route::get('/', FlashCardTable::class)->name('index');
    Route::get('create', FlashCardUpdateOrCreate::class)->name('create')->can('create,App\Models\FlashCard');
    Route::get('{flashCard}/edit', FlashCardUpdateOrCreate::class)->name('edit')->can('update,flashCard');
});
