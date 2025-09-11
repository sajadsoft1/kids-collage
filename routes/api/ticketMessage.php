<?php

declare(strict_types=1);

use App\Http\Controllers\Api\TicketMessageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ticket-message', 'as' => 'ticket-message.'], function () {
    // Route::post('toggle/{ticketMessage}', [TicketMessageController::class, 'toggle'])->name('toggle');
    // Route::get('data', [TicketMessageController::class, 'extraData'])->name('data');
});
Route::apiResource('ticket-message', TicketMessageController::class);
