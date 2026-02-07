<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\TicketChat\TicketCreate;
use App\Livewire\Admin\Pages\TicketChat\TicketDepartmentIndex;
use App\Livewire\Admin\Pages\TicketChat\TicketDepartmentUpdateOrCreate;
use App\Livewire\Admin\Pages\TicketChat\TicketList;
use App\Livewire\Admin\Pages\TicketChat\TicketShow;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/ticket-chat', 'as' => 'admin.ticket-chat.'], function () {
    Route::get('/', TicketList::class)->name('index');
    Route::get('/create', TicketCreate::class)->name('create');
    Route::get('/departments', TicketDepartmentIndex::class)->name('departments.index');
    Route::get('/departments/create', TicketDepartmentUpdateOrCreate::class)->name('departments.create');
    Route::get('/departments/{ticket_department}/edit', TicketDepartmentUpdateOrCreate::class)->name('departments.edit');
    Route::get('/{ticket}', TicketShow::class)->name('show')->where('ticket', '[0-9]+');
});
