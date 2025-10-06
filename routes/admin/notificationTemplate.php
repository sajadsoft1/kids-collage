<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\NotificationTemplate\NotificationTemplateUpdateOrCreate;
use App\Livewire\Admin\Pages\NotificationTemplate\NotificationTemplateTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/notification-template', 'as' => 'admin.notification-template.'], function () {
    Route::get('/', NotificationTemplateTable::class)->name('index');
    Route::get('create', NotificationTemplateUpdateOrCreate::class)->name('create')->can('create,App\Models\NotificationTemplate');
    Route::get('{notification-template}/edit', NotificationTemplateUpdateOrCreate::class)->name('edit')->can('update,notification-template');
});
