<?php

declare(strict_types=1);

use App\Livewire\Admin\Apps\BoardList;
use App\Livewire\Admin\Apps\CalendarApp;
use App\Livewire\Admin\Apps\KanbanApp;
use App\Livewire\Admin\Apps\UserProfileApp;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/app', 'as' => 'admin.app.'], function () {
    Route::get('calendar', CalendarApp::class)->name('calendar');
    Route::get('kanban/{board?}', KanbanApp::class)->name('kanban');
    Route::get('boards', BoardList::class)->name('boards');
    Route::get('profile/{user?}', UserProfileApp::class)->name('profile');
});
