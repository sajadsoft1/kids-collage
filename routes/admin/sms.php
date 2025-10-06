<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Sms\SmsUpdateOrCreate;
use App\Livewire\Admin\Pages\Sms\SmsTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/sms', 'as' => 'admin.sms.'], function () {
    Route::get('/', SmsTable::class)->name('index');
    Route::get('create', SmsUpdateOrCreate::class)->name('create')->can('create,App\Models\Sms');
    Route::get('{sms}/edit', SmsUpdateOrCreate::class)->name('edit')->can('update,sms');
});
