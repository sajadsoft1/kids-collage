<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\TicketChat\TicketCreate;
use App\Livewire\Admin\Pages\TicketChat\TicketDepartmentIndex;
use App\Livewire\Admin\Pages\TicketChat\TicketDepartmentUpdateOrCreate;
use App\Livewire\Admin\Pages\TicketChat\TicketFeedbackReport;
use App\Livewire\Admin\Pages\TicketChat\TicketList;
use App\Livewire\Admin\Pages\TicketChat\TicketShow;
use App\Livewire\Admin\Pages\TicketChat\TicketTagIndex;
use App\Livewire\Admin\Pages\TicketChat\TicketTagUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/ticket-chat', 'as' => 'admin.ticket-chat.'], function () {
    Route::get('/', TicketList::class)->name('index');
    Route::get('/create', TicketCreate::class)->name('create');
    Route::get('/feedback-report', TicketFeedbackReport::class)->name('feedback-report');
    Route::get('/departments', TicketDepartmentIndex::class)->name('departments.index');
    Route::get('/departments/create', TicketDepartmentUpdateOrCreate::class)->name('departments.create');
    Route::get('/departments/{ticket_department}/edit', TicketDepartmentUpdateOrCreate::class)->name('departments.edit');
    Route::get('/tags', TicketTagIndex::class)->name('tags.index');
    Route::get('/tags/create', TicketTagUpdateOrCreate::class)->name('tags.create');
    Route::get('/tags/{ticket_tag}/edit', TicketTagUpdateOrCreate::class)->name('tags.edit');
    Route::get('/{ticket}', TicketShow::class)->name('show')->where('ticket', '[0-9]+');
});
