<?php

declare(strict_types=1);

use App\Http\Controllers\Api\NoteController;
use Illuminate\Support\Facades\Route;

Route::apiResource('note', NoteController::class);
