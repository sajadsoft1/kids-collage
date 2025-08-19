<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\ContactUs\ContactUsTable;
use App\Livewire\Admin\Pages\ContactUs\ContactUsUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/contact-us', 'as' => 'admin.contact-us.'], function () {
    Route::get('/', ContactUsTable::class)->name('index');
    Route::get('create', ContactUsUpdateOrCreate::class)->name('create')->can('create,App\Models\ContactUs');
    Route::get('{contactUs}/edit', ContactUsUpdateOrCreate::class)->name('edit')->can('update,contactUs');
});
