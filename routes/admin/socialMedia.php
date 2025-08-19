<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\SocialMedia\SocialMediaTable;
use App\Livewire\Admin\Pages\SocialMedia\SocialMediaUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/social-media', 'as' => 'admin.social-media.'], function () {
    Route::get('/', SocialMediaTable::class)->name('index');
    Route::get('create', SocialMediaUpdateOrCreate::class)->name('create')->can('create,App\Models\SocialMedia');
    Route::get('{socialMedia}/edit', SocialMediaUpdateOrCreate::class)->name('edit')->can('update,socialMedia');
});
