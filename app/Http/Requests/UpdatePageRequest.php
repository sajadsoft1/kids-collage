<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="UpdatePageRequest",
 *      title="Update Page request",
 *      type="object",
 *      required={"title", "body", "type", "slug", "seo_title", "seo_description", "robots_meta"},
 *
 *     @OA\Property(property="title", type="string", default="Updated About Us", description="Page title"),
 *     @OA\Property(property="body", type="string", default="<p>Updated content about our company...</p>", description="Page content body"),
 *     @OA\Property(property="type", type="string", enum={"rules", "about-us"}, default="about-us", description="Page type"),
 *     @OA\Property(property="slug", type="string", default="updated-about-us", description="Page slug/URL"),
 *     @OA\Property(property="seo_title", type="string", default="Updated About Us - Company Name", description="SEO title"),
 *     @OA\Property(property="seo_description", type="string", default="Learn more about our updated company info", description="SEO description"),
 *     @OA\Property(property="canonical", type="string", default="https://example.com/about-us", description="Canonical URL"),
 *     @OA\Property(property="old_url", type="string", description="Old URL for redirect"),
 *     @OA\Property(property="redirect_to", type="string", description="Redirect target URL"),
 *     @OA\Property(property="robots_meta", type="string", default="index_follow", enum={"index_follow", "noindex_nofollow", "noindex_follow"}, description="Robots meta tag"),
 *     @OA\Property(property="image", type="string", format="binary", description="Page featured image"),
 * )
 */
class UpdatePageRequest extends StorePageRequest {}
