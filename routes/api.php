<?php

declare(strict_types=1);

use App\Http\Middleware\SetApiGuard;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.', 'middleware' => [SetApiGuard::class]], function () {
    $path = __DIR__ . '/api';
    foreach (array_diff(scandir($path, SCANDIR_SORT_NONE), ['.', '..']) as $file) {
        require_once "api/{$file}";
    }
});
