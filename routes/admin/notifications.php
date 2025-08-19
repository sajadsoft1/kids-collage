<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Notification\NotificationTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/notification', 'as' => 'admin.notification.'], function () {
    Route::get('/', NotificationTable::class)->name('index');
});
