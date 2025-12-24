<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seo_options', function (Blueprint $table) {
            $table->boolean('sitemap_exclude')->default(false)->after('author');
            // Purpose: Exclude this page from sitemap generation.
            // Usage: Set to true to prevent the page from appearing in XML sitemaps.

            $table->decimal('sitemap_priority', 2, 1)->nullable()->after('sitemap_exclude');
            // Purpose: Set sitemap priority (0.0 to 1.0).
            // Usage: Higher priority pages are crawled more frequently by search engines.

            $table->string('sitemap_changefreq', 20)->nullable()->after('sitemap_priority');
            // Purpose: Set how frequently the page is expected to change.
            // Usage: Values: always, hourly, daily, weekly, monthly, yearly, never.
        });
    }

    public function down(): void
    {
        Schema::table('seo_options', function (Blueprint $table) {
            $table->dropColumn(['sitemap_exclude', 'sitemap_priority', 'sitemap_changefreq']);
        });
    }
};
