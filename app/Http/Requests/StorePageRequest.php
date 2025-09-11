<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PageTypeEnum;
use App\Enums\SeoRobotsMetaEnum;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StorePageRequest",
 *     title="Store Page request",
 *     type="object",
 *     required={"title", "body", "type", "slug", "seo_title", "seo_description", "robots_meta"},
 *
 *     @OA\Property(property="title", type="string", default="About Us", description="Page title"),
 *     @OA\Property(property="body", type="string", default="<p>Welcome to our company...</p>", description="Page content body"),
 *     @OA\Property(property="type", type="string", enum={"rules", "about-us"}, default="about-us", description="Page type"),
 *     @OA\Property(property="slug", type="string", default="about-us", description="Page slug/URL"),
 *     @OA\Property(property="seo_title", type="string", default="About Us - Company Name", description="SEO title"),
 *     @OA\Property(property="seo_description", type="string", default="Learn more about our company", description="SEO description"),
 *     @OA\Property(property="canonical", type="string", default="https://example.com/about-us", description="Canonical URL"),
 *     @OA\Property(property="old_url", type="string", description="Old URL for redirect"),
 *     @OA\Property(property="redirect_to", type="string", description="Redirect target URL"),
 *     @OA\Property(property="robots_meta", type="string", default="index_follow", enum={"index_follow", "noindex_nofollow", "noindex_follow"}, description="Robots meta tag"),
 *     @OA\Property(property="image", type="string", format="binary", description="Page featured image"),
 * )
 */
class StorePageRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'body'            => ['required', 'string'],
            'type'            => 'required|in:' . implode(',', PageTypeEnum::values()),
            'slug'            => 'required|unique:pages,slug',
            'seo_title'       => 'required|string|max:255',
            'seo_description' => 'required|string|max:255',
            'canonical'       => 'nullable|url',
            'old_url'         => 'nullable|url',
            'redirect_to'     => 'nullable|url',
            'robots_meta'     => 'required|in:' . implode(',', SeoRobotsMetaEnum::values()),
            'image'           => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Page doesn't use published field based on action
    }
}
