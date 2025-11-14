<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\NotificationTemplate\NotificationTemplateTable;
use App\Livewire\Admin\Pages\NotificationTemplate\NotificationTemplateUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/notification-template', 'as' => 'admin.notification-template.'], function () {
    Route::get('/', NotificationTemplateTable::class)->name('index');
    Route::get('create', NotificationTemplateUpdateOrCreate::class)->name('create')->can('create,App\Models\NotificationTemplate');
    Route::get('{notificationTemplate}/edit', NotificationTemplateUpdateOrCreate::class)->name('edit')->can('update,notificationTemplate');
});
