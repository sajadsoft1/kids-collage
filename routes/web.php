<?php

declare(strict_types=1);

use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\TokenLoginAction;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UtilityController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Livewire\Web\Auth\ConfirmPage;
use App\Livewire\Web\Auth\ForgetPasswordPage;
use App\Livewire\Web\Auth\LoginPage;
use App\Livewire\Web\Auth\RegisterPage;
use App\Livewire\Web\Pages\AboutUsPage;
use App\Livewire\Web\Pages\BlogDetailPage;
use App\Livewire\Web\Pages\BlogPage;
use App\Livewire\Web\Pages\ContactUsPage;
use App\Livewire\Web\Pages\CourseDetailPage;
use App\Livewire\Web\Pages\CoursePage;
use App\Livewire\Web\Pages\EventDetailPage;
use App\Livewire\Web\Pages\EventPage;
use App\Livewire\Web\Pages\FaqPage;
use App\Livewire\Web\Pages\HomePage;
use App\Livewire\Web\Pages\NewsDetailPage;
use App\Livewire\Web\Pages\NewsPage;
use App\Livewire\Web\Pages\SearchPage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

// Route::get('change-locale/{lang}', [UtilityController::class, 'changeLocale'])->name('change-locale');
//
// // Logout Route
// Route::post('logout', function () {
//    Auth::logout();
//
//    return redirect()->route('login')->with('success', 'Successfully logged out.');
// })->name('logout');
//
// // Web Authentication Routes (Redirect authenticated users)
// Route::middleware(RedirectIfAuthenticated::class)->group(function () {
//    Route::multilingual('login', LoginPage::class)->name('login');
//    Route::multilingual('register', RegisterPage::class)->name('register');
//    Route::multilingual('forget-password', ForgetPasswordPage::class)->name('password.request');
//    Route::multilingual('confirm', ConfirmPage::class)->name('password.confirm');
//
//    // Google OAuth Routes
//    Route::get('auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
//    Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
// });

// Web Pages
Route::multilingual('/', HomePage::class)->name('home-page');
Route::multilingual('blog', BlogPage::class)->name('blog');
Route::multilingual('blog/{blog:slug}', BlogDetailPage::class)->name('blog.detail');
//
// // Course Pages
// Route::multilingual('course', BlogPage::class)->name('blog');
// Route::multilingual('course/{slug}', BlogDetailPage::class)->name('courseTemplate.detail');
//
// // search by:text|category|tag|author
// Route::multilingual('search', SearchPage::class)->name('search');
//
// Route::multilingual('news', NewsPage::class)->name('news');
// Route::multilingual('news/{news}', NewsDetailPage::class)->name('news.detail');
//
// Route::multilingual('event', EventPage::class)->name('event');
// Route::multilingual('event/{event}', EventDetailPage::class)->name('event.detail');
//
// Route::multilingual('course', CoursePage::class)->name('course');
// Route::multilingual('course/{course}', CourseDetailPage::class)->name('course.detail');
//
// Route::multilingual('faq', FaqPage::class)->name('faq');
// Route::multilingual('contact', ContactUsPage::class)->name('contact');
// Route::multilingual('about', AboutUsPage::class)->name('about');

//
// Route::multilingual('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
// Route::multilingual('sitemap-article.xml', [SitemapController::class, 'article'])->name('sitemap-article');
Auth::login(User::find(4));
Route::get('test', function () {
    return new App\Mail\NotificationMail([
        'title' => 'New Notification',
        'subtitle' => 'This is a subtitle',
        'body' => 'This is a body',
        'rtl' => true,
        'cta' => [
            'url' => 'https://www.google.com',
            'label' => 'Click here',
        ],
    ]);
});

Route::get('sync-session/{token}', function (Request $request, string $token) {
    $user = TokenLoginAction::run($token);

    if ( ! $user) {
        abort(401, 'Invalid or expired token');
    }

    // Redirect to admin dashboard
    return redirect(config('app.frontend_url') . $request->input('intent'));
})->name('auth.token-login');

Route::get('remove-session/{token}', function (Request $request, string $token) {
    $accessToken = PersonalAccessToken::findToken($token);

    if ( ! $accessToken) {
        abort(401, 'Invalid or expired token');
    }
    $user = $accessToken->tokenable;

    if ( ! $user) {
        abort(401, 'Invalid or expired token');
    }
    LogoutAction::run($user);

    return redirect(config('app.frontend_url') . $request->input('intent'));
})->name('auth.logout');

// laravel not found route
Route::fallback(function () {
    return redirect('/admin');
});
