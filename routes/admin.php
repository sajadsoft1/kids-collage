<?php

declare(strict_types=1);

use App\Livewire\Admin\Auth\LoginPage;
use App\Livewire\Admin\Pages\Dashboard\DashboardIndex;
use App\Livewire\Admin\Pages\Setting\SettingList;
use App\Livewire\Admin\Shared\DynamicTranslate;
use Illuminate\Support\Facades\Route;

// Volt::route('/', 'users.index');

Route::get('admin/auth/login', LoginPage::class)->name('admin.auth.login');
Route::get('admin/auth/logout', function () {
    auth()->logout();

    return redirect()->route('admin.auth.login');
})->name('admin.auth.logout');

Route::group(['middleware' => ['admin.panel']], function () {
    Route::get('admin', DashboardIndex::class)->name('admin.dashboard');

    Route::get('admin/setting', SettingList::class)->name('admin.setting');

    Route::get('utilitys/translate/{class}/{id}', DynamicTranslate::class)->name('admin.dynamic-translate');

    $files = array_diff(scandir(__DIR__ . '/admin', SCANDIR_SORT_ASCENDING), ['.', '..']);
    foreach ($files as $file_name) {
        require_once sprintf('admin/%s', $file_name);
    }
});
