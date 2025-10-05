<?php

declare(strict_types=1);

use App\Livewire\Admin\Auth\LoginPage;
use App\Livewire\Admin\Pages\Dashboard\DashboardIndex;
use App\Livewire\Admin\Pages\Setting\SettingList;
use App\Livewire\Admin\Shared\DynamicSeo;
use App\Livewire\Admin\Shared\DynamicTranslate;
use Illuminate\Support\Facades\Route;

// Volt::route('/', 'users.index');

Route::get('admin/auth/login', LoginPage::class)->name('admin.auth.login');
Route::get('admin/auth/logout', function () {
    auth()->guard('web')->logout();

    return redirect()->route('admin.auth.login');
})->name('admin.auth.logout');

Route::group(['middleware' => ['admin.panel']], function () {
    Route::get('admin', DashboardIndex::class)->name('admin.dashboard');

    Route::get('admin/setting', SettingList::class)->name('admin.setting');

    Route::get('utilitys/translate/{class}/{id}', DynamicTranslate::class)->name('admin.dynamic-translate');
    Route::get('utility/seo/{class}/{id}', DynamicSeo::class)->name('admin.dynamic-seo');
    require_once __DIR__ . '/admin/apps.php';
    require_once __DIR__ . '/admin/attendance.php';
    require_once __DIR__ . '/admin/banner.php';
    require_once __DIR__ . '/admin/blog.php';
    require_once __DIR__ . '/admin/bulletin.php';
    require_once __DIR__ . '/admin/category.php';
    require_once __DIR__ . '/admin/client.php';
    require_once __DIR__ . '/admin/comment.php';
    require_once __DIR__ . '/admin/contactUs.php';
    require_once __DIR__ . '/admin/course.php';
    require_once __DIR__ . '/admin/courseSession.php';
    require_once __DIR__ . '/admin/courseSessionTemplate.php';
    require_once __DIR__ . '/admin/courseTemplate.php';
    require_once __DIR__ . '/admin/enrollment.php';
    require_once __DIR__ . '/admin/faq.php';
    require_once __DIR__ . '/admin/installment.php';
    require_once __DIR__ . '/admin/license.php';
    require_once __DIR__ . '/admin/notifications.php';
    require_once __DIR__ . '/admin/opinion.php';
    require_once __DIR__ . '/admin/order.php';
    require_once __DIR__ . '/admin/page.php';
    require_once __DIR__ . '/admin/payment.php';
    require_once __DIR__ . '/admin/portFolio.php';
    require_once __DIR__ . '/admin/role.php';
    require_once __DIR__ . '/admin/room.php';
    require_once __DIR__ . '/admin/seoOption.php';
    require_once __DIR__ . '/admin/slider.php';
    require_once __DIR__ . '/admin/socialMedia.php';
    require_once __DIR__ . '/admin/tag.php';
    require_once __DIR__ . '/admin/teammate.php';
    require_once __DIR__ . '/admin/term.php';
    require_once __DIR__ . '/admin/ticket.php';
    require_once __DIR__ . '/admin/user.php';
});
