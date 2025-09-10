<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.'], function () {
    $path = __DIR__ . '/api';
    foreach (array_diff(scandir($path, SCANDIR_SORT_NONE), ['.', '..']) as $file) {
        require_once "api/$file";
    }
});
