<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateTagRequest",
 *     title="Update Tag request",
 *     type="object",
 *     required={"name", "seo_title", "seo_description", "robots_meta"},
 *
 *     @OA\Property(property="name", type="string", default="mobile-development", description="Tag name"),
 *     @OA\Property(property="description", type="string", default="Updated mobile development services", description="Tag description"),
 *     @OA\Property(property="body", type="string", default="<p>Updated detailed tag content...</p>", description="Tag detailed content"),
 *     @OA\Property(property="type", type="string", enum={"special"}, description="Tag type"),
 *     @OA\Property(property="order_column", type="integer", default=1, description="Tag order"),
 *     @OA\Property(property="seo_title", type="string", default="Mobile Development | Company Name", description="SEO title"),
 *     @OA\Property(property="seo_description", type="string", default="Professional mobile development services", description="SEO description"),
 *     @OA\Property(property="canonical", type="string", default="https://example.com/tags/mobile-development", description="Canonical URL"),
 *     @OA\Property(property="old_url", type="string", description="Old URL for redirect"),
 *     @OA\Property(property="redirect_to", type="string", description="Redirect target URL"),
 *     @OA\Property(property="robots_meta", type="string", default="index_follow", enum={"index_follow", "noindex_nofollow", "noindex_follow"}, description="Robots meta tag"),
 *     @OA\Property(property="image", type="string", format="binary", description="Tag featured image"),
 * )
 */
class UpdateTagRequest extends StoreTagRequest {}
