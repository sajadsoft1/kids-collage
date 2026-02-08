<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Term\TermDetail;
use App\Livewire\Admin\Pages\Term\TermTable;
use App\Livewire\Admin\Pages\Term\TermUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/term', 'as' => 'admin.term.'], function () {
    Route::get('/', TermTable::class)->name('index');
    Route::get('create', TermUpdateOrCreate::class)->name('create')->can('create,App\Models\Term');
    Route::get('{term}/show', TermDetail::class)->name('show');
    Route::get('{term}/edit', TermUpdateOrCreate::class)->name('edit')->can('update,term');
});
