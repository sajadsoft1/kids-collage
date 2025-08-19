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
            
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('seo_options');
    }
};
