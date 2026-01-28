<?php

declare(strict_types=1);

use App\Http\Controllers\Api\FlashCardController;
use Illuminate\Support\Facades\Route;

Route::get('flash-card/extra-data', [FlashCardController::class, 'detailsIndex']);
Route::apiResource('flash-card', FlashCardController::class)->parameter('flash-card', 'flashCard')->names('flash-card');
