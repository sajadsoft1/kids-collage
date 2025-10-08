<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Enrollment\EnrollmentTable;
use App\Livewire\Admin\Pages\Enrollment\EnrollmentUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/enrollment', 'as' => 'admin.enrollment.'], function () {
    Route::get('/', EnrollmentTable::class)->name('index');
    Route::get('create', EnrollmentUpdateOrCreate::class)->name('create')->can('create,App\Models\Enrollment');
    Route::get('{enrollment}/edit', EnrollmentUpdateOrCreate::class)->name('edit')->can('update,enrollment');
});
