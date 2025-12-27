<?php

declare(strict_types=1);

use App\Enums\SeoRobotsMetaEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seo_options', function (Blueprint $table) {
            $table->id();

            $table->morphs('morphable');

            $table->string('title', 255);
            // Purpose: Contains the SEO title for the page.
            // Usage: Displayed in search engine results as the clickable title link.

            $table->text('description')->nullable();
            // Purpose: Short summary of the page content for search engines.
            // Usage: Encourages users to click on the link in search results.

            $table->string('canonical')->nullable();
            // Purpose: Specifies the canonical version of a URL to prevent duplicate content issues.
            // Usage: Guides search engines to prioritize a specific version of the URL.

            $table->string('old_url')->nullable();
            // Purpose: Stores the old URL before migration, allowing for proper mapping of 301 redirects.
            // Usage: Useful during URL restructuring to avoid broken links.

            $table->string('redirect_to')->nullable();
            // Purpose: Stores the URL to which the old URL should be redirected.
            // Usage: Handles 301 redirects during migration.

            $table->string('robots_meta')->default(SeoRobotsMetaEnum::INDEX_FOLLOW);
            // Purpose: Defines how search engines should index and crawl the page.
            // options:
            // (index_follow): Index the page and follow its links.
            // (noindex_nofollow): Don't index the page or follow its links.
            // (noindex_follow): Don't index the page but follow its links.

            $table->string('og_image')->nullable();
            // Purpose: Custom Open Graph image for social media sharing.
            // Usage: Overrides default image when sharing on Facebook, LinkedIn, etc.

            $table->string('twitter_image')->nullable();
            // Purpose: Custom Twitter Card image for Twitter sharing.
            // Usage: Optimized image specifically for Twitter cards.

            $table->string('focus_keyword')->nullable();
            // Purpose: Primary keyword for SEO optimization.
            // Usage: Helps track keyword density and optimization.

            $table->text('meta_keywords')->nullable();
            // Purpose: Additional keywords for meta tags (optional, less important for modern SEO).
            // Usage: Comma-separated list of relevant keywords.

            $table->string('author')->nullable();
            // Purpose: Author name for articles and content.
            // Usage: Used in Article schema and author attribution.

            $table->boolean('sitemap_exclude')->default(false);
            // Purpose: Exclude this page from sitemap generation.
            // Usage: Set to true to prevent the page from appearing in XML sitemaps.

            $table->decimal('sitemap_priority', 2, 1)->nullable();
            // Purpose: Set sitemap priority (0.0 to 1.0).
            // Usage: Higher priority pages are crawled more frequently by search engines.

            $table->string('sitemap_changefreq', 20)->nullable();
            // Purpose: Set how frequently the page is expected to change.
            // Usage: Values: always, hourly, daily, weekly, monthly, yearly, never.

            $table->string('image_alt')->nullable();
            // Purpose: Alt text for the main image of the page.
            // Usage: Important for SEO and accessibility. Should describe the image content.

            $table->string('image_title')->nullable();
            // Purpose: Title attribute for the main image of the page.
            // Usage: Provides additional context when hovering over the image.

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_options');
    }
};
