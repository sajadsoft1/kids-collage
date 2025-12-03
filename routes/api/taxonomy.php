<?php

declare(strict_types=1);

use App\Http\Controllers\Api\TaxonomyController;
use Illuminate\Support\Facades\Route;

Route::apiResource('taxonomy', TaxonomyController::class);
