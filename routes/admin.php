<?php

declare(strict_types=1);

use App\Actions\Auth\LogoutAction;
use App\Livewire\Admin\Auth\LoginPage;
use App\Livewire\Admin\Pages\Dashboard\DashboardIndex;
use App\Livewire\Admin\Pages\Setting\SettingList;
use App\Livewire\Admin\Pages\Test\TestChart;
use App\Livewire\Admin\Shared\DynamicSeo;
use App\Livewire\Admin\Shared\DynamicTranslate;
use App\Livewire\Admin\Test\ErrorHandlingDemo;
use App\Livewire\Admin\Test\FeatureModule;
use Illuminate\Support\Facades\Route;

// Volt::route('/', 'users.index');

Route::get('admin/auth/login', function () {
    return redirect(config('app.frontend_url') . '/login');
})->name('admin.auth.login');

// Route::get('admin/auth/login', LoginPage::class)->name('admin.auth.login');
Route::get('admin/auth/logout', function () {
    LogoutAction::run(auth()->user());

    return redirect(config('app.frontend_url') . '/login');
})->name('admin.auth.logout');

Route::group(['middleware' => ['admin.panel']], function () {
    Route::get('admin', DashboardIndex::class)->name('admin.dashboard');
    Route::get('admin/setting', SettingList::class)->name('admin.setting');

    // Test route for error handling demo (only in development)
    if (config('app.debug')) {
        Route::get('admin/test/chart', TestChart::class)->name('admin.test.chart');
        Route::get('admin/test/error-handling', ErrorHandlingDemo::class)->name('admin.test.error-handling');
    }
    Route::get('admin/feature-module/{module}', FeatureModule::class)->name('admin.feature-module');

    Route::get('utilitys/translate/{class}/{id}', DynamicTranslate::class)->name('admin.dynamic-translate');
    Route::get('utility/seo/{class}/{id}', DynamicSeo::class)->name('admin.dynamic-seo');
    require __DIR__ . '/admin/apps.php';
    require __DIR__ . '/admin/attendance.php';
    require __DIR__ . '/admin/banner.php';
    require __DIR__ . '/admin/blog.php';
    require __DIR__ . '/admin/bulletin.php';
    require __DIR__ . '/admin/category.php';
    require __DIR__ . '/admin/client.php';
    require __DIR__ . '/admin/comment.php';
    require __DIR__ . '/admin/contactUs.php';
    require __DIR__ . '/admin/course.php';
    require __DIR__ . '/admin/courseSession.php';
    require __DIR__ . '/admin/courseSessionTemplate.php';
    require __DIR__ . '/admin/courseTemplate.php';
    require __DIR__ . '/admin/resource.php';
    require __DIR__ . '/admin/discount.php';
    require __DIR__ . '/admin/enrollment.php';
    require __DIR__ . '/admin/faq.php';
    require __DIR__ . '/admin/license.php';
    require __DIR__ . '/admin/notifications.php';
    require __DIR__ . '/admin/notificationTemplate.php';
    require __DIR__ . '/admin/opinion.php';
    require __DIR__ . '/admin/order.php';
    require __DIR__ . '/admin/page.php';
    require __DIR__ . '/admin/payment.php';
    require __DIR__ . '/admin/portFolio.php';
    require __DIR__ . '/admin/role.php';
    require __DIR__ . '/admin/room.php';
    require __DIR__ . '/admin/seoOption.php';
    require __DIR__ . '/admin/slider.php';
    require __DIR__ . '/admin/sms.php';
    require __DIR__ . '/admin/socialMedia.php';
    require __DIR__ . '/admin/tag.php';
    require __DIR__ . '/admin/teammate.php';
    require __DIR__ . '/admin/term.php';
    require __DIR__ . '/admin/ticket.php';
    require __DIR__ . '/admin/user.php';
    require __DIR__ . '/admin/question.php';
    require __DIR__ . '/admin/questionSubject.php';
    require __DIR__ . '/admin/questionCompetency.php';
    require __DIR__ . '/admin/questionOption.php';
    require __DIR__ . '/admin/questionSystem.php';
    require __DIR__ . '/admin/exam.php';
    require __DIR__ . '/admin/survey.php';
    require __DIR__ . '/admin/event.php';
    require __DIR__ . '/admin/flashCard.php';
    require __DIR__ . '/admin/notebook.php';
});
