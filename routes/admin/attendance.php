<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Attendance\AttendanceBySessionTable;
use App\Livewire\Admin\Pages\Attendance\AttendanceByStudentTable;
use App\Livewire\Admin\Pages\Attendance\AttendanceTable;
use App\Livewire\Admin\Pages\Attendance\AttendanceUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/attendance', 'as' => 'admin.attendance.'], function () {
    Route::get('/', AttendanceTable::class)->name('index');
    Route::get('by-student', AttendanceByStudentTable::class)->name('by-student');
    Route::get('by-session', AttendanceBySessionTable::class)->name('by-session');
    Route::get('create', AttendanceUpdateOrCreate::class)->name('create')->can('create,App\Models\Attendance');
    Route::get('{attendance}/edit', AttendanceUpdateOrCreate::class)->name('edit')->can('update,attendance');
});
