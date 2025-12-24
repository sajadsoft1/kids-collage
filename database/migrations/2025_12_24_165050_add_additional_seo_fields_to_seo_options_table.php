<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seo_options', function (Blueprint $table) {
            $table->string('og_image')->nullable()->after('description');
            // Purpose: Custom Open Graph image for social media sharing.
            // Usage: Overrides default image when sharing on Facebook, LinkedIn, etc.

            $table->string('twitter_image')->nullable()->after('og_image');
            // Purpose: Custom Twitter Card image for Twitter sharing.
            // Usage: Optimized image specifically for Twitter cards.

            $table->string('focus_keyword')->nullable()->after('twitter_image');
            // Purpose: Primary keyword for SEO optimization.
            // Usage: Helps track keyword density and optimization.

            $table->text('meta_keywords')->nullable()->after('focus_keyword');
            // Purpose: Additional keywords for meta tags (optional, less important for modern SEO).
            // Usage: Comma-separated list of relevant keywords.

            $table->string('author')->nullable()->after('meta_keywords');
            // Purpose: Author name for articles and content.
            // Usage: Used in Article schema and author attribution.
        });
    }

    public function down(): void
    {
        Schema::table('seo_options', function (Blueprint $table) {
            $table->dropColumn(['og_image', 'twitter_image', 'focus_keyword', 'meta_keywords', 'author']);
        });
    }
};
