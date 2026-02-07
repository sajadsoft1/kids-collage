<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\CertificateTemplate\CertificateTemplateTable;
use App\Livewire\Admin\Pages\CertificateTemplate\CertificateTemplateUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/certificate-template', 'as' => 'admin.certificate-template.'], function () {
    Route::get('/', CertificateTemplateTable::class)->name('index');
    Route::get('create', CertificateTemplateUpdateOrCreate::class)->name('create')->can('create', App\Models\CertificateTemplate::class);
    Route::get('{certificateTemplate}/edit', CertificateTemplateUpdateOrCreate::class)->name('edit')->can('update', 'certificateTemplate');
});
