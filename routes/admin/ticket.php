<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Ticket\TicketApp;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/ticket', 'as' => 'admin.ticket.'], function () {
    Route::get('/', TicketApp::class)->name('index');
});
