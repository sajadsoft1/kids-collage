<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/exams/self-assessment', [App\Http\Controllers\Api\SelfAssessmentController::class, 'index']);
Route::get('/exams/self-assessment/{exam}', [App\Http\Controllers\Api\SelfAssessmentController::class, 'show']);
