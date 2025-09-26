<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Attendance\AttendanceUpdateOrCreate;
use App\Livewire\Admin\Pages\Attendance\AttendanceTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/attendance', 'as' => 'admin.attendance.'], function () {
    Route::get('/', AttendanceTable::class)->name('index');
    Route::get('create', AttendanceUpdateOrCreate::class)->name('create')->can('create,App\Models\Attendance');
    Route::get('{attendance}/edit', AttendanceUpdateOrCreate::class)->name('edit')->can('update,attendance');
});
