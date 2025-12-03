<?php

declare(strict_types=1);

use App\Http\Middleware\SetApiGuard;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.', 'middleware' => [SetApiGuard::class]], function () {
    // Include all API route files statically for route caching compatibility
    require __DIR__ . '/api/auth.php';
    require __DIR__ . '/api/banner.php';
    require __DIR__ . '/api/blog.php';
    require __DIR__ . '/api/client.php';
    require __DIR__ . '/api/comment.php';
    require __DIR__ . '/api/faq.php';
    require __DIR__ . '/api/home.php';
    require __DIR__ . '/api/user.php';
    require __DIR__ . '/api/contact.php';
    require __DIR__ . '/api/about.php';
    require __DIR__ . '/api/bulletin.php';
    require __DIR__ . '/api/rule.php';
    require __DIR__ . '/api/license.php';
    require __DIR__ . '/api/courseTemplate.php';
    require __DIR__ . '/api/socialMedia.php';
    require __DIR__ . '/api/event.php';
    require __DIR__ . '/api/flashCard.php';
    require __DIR__ . '/api/notebook.php';
    require __DIR__ . '/api/note.php';
    require __DIR__ . '/api/taxonomy.php';
});
