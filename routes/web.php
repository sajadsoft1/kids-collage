<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UtilityController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Livewire\Web\Auth\ConfirmPage;
use App\Livewire\Web\Auth\ForgetPasswordPage;
use App\Livewire\Web\Auth\LoginPage;
use App\Livewire\Web\Auth\RegisterPage;
use App\Livewire\Web\Pages\BlogDetailPage;
use App\Livewire\Web\Pages\BlogPage;
use App\Livewire\Web\Pages\ContactUsPage;
use App\Livewire\Web\Pages\FaqPage;
use App\Livewire\Web\Pages\HomePage;
use App\Livewire\Web\Pages\PortfolioDetailPage;
use App\Livewire\Web\Pages\PortfolioPage;
use App\Livewire\Web\Pages\Services\ApplicationPage;
use App\Livewire\Web\Pages\Services\SeoPage;
use App\Livewire\Web\Pages\Services\WebsitePage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('change-locale/{lang}', [UtilityController::class, 'changeLocale'])->name('change-locale');

// Logout Route
Route::post('logout', function () {
    Auth::logout();

    return redirect()->route('login')->with('success', 'Successfully logged out.');
})->name('logout');

// Web Authentication Routes (Redirect authenticated users)
Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::multilingual('login', LoginPage::class)->name('login');
    Route::multilingual('register', RegisterPage::class)->name('register');
    Route::multilingual('forget-password', ForgetPasswordPage::class)->name('password.request');
    Route::multilingual('confirm', ConfirmPage::class)->name('password.confirm');

    // Google OAuth Routes
    Route::get('auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

// Web Pages
Route::multilingual('/', HomePage::class)->name('home-page');
Route::multilingual('blog', BlogPage::class)->name('blog');
Route::multilingual('blog/detail', BlogDetailPage::class)->name('blog.detail');
Route::multilingual('portfolio', PortfolioPage::class)->name('portfolio');
Route::multilingual('faq', FaqPage::class)->name('faq');
Route::multilingual('portfolio/detail', PortfolioDetailPage::class)->name('portfolio.detail');
Route::multilingual('services/website', WebsitePage::class)->name('services.website');
Route::multilingual('services/seo', SeoPage::class)->name('services.seo');
Route::multilingual('services/application', ApplicationPage::class)->name('services.application');
Route::multilingual('contact-us', ContactUsPage::class)->name('contact-us');
Route::multilingual('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::multilingual('sitemap-article.xml', [SitemapController::class, 'article'])->name('sitemap-article');
Route::multilingual('sitemap-portfolio.xml', [SitemapController::class, 'portfolio'])->name('sitemap-portfolio');

// laravel not found route
//Route::fallback(function () {
//    return redirect(localized_route('home-page'));
//});
