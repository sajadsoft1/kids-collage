<?php

declare(strict_types=1);

use App\Http\Controllers\Api\NotebookController;
use Illuminate\Support\Facades\Route;

Route::apiResource('notebook', NotebookController::class);
