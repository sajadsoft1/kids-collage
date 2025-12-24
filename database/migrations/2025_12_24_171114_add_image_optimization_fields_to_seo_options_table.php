<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seo_options', function (Blueprint $table) {
            $table->string('image_alt')->nullable()->after('sitemap_changefreq');
            // Purpose: Alt text for the main image of the page.
            // Usage: Important for SEO and accessibility. Should describe the image content.

            $table->string('image_title')->nullable()->after('image_alt');
            // Purpose: Title attribute for the main image of the page.
            // Usage: Provides additional context when hovering over the image.
        });
    }

    public function down(): void
    {
        Schema::table('seo_options', function (Blueprint $table) {
            $table->dropColumn(['image_alt', 'image_title']);
        });
    }
};
