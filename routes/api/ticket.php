<?php

declare(strict_types=1);

use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
    // Route::post('toggle/{ticket}', [TicketController::class, 'toggle'])->name('toggle');
    // Route::get('data', [TicketController::class, 'extraData'])->name('data');
});
Route::apiResource('ticket', TicketController::class);
