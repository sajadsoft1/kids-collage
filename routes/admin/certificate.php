<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Certificate\CertificateTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/certificate', 'as' => 'admin.certificate.'], function () {
    Route::get('/', CertificateTable::class)->name('index');
});
