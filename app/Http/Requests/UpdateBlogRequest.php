<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateBlogRequest",
 *     title="Update Blog request",
 *     type="object",
 *     required={"title", "description", "published", "category_id", "slug", "seo_title", "seo_description", "robots_meta"},
 *
 *     @OA\Property(property="title", type="string", default="Updated blog title", description="Blog title"),
 *     @OA\Property(property="description", type="string", default="Updated blog description", description="Blog description"),
 *     @OA\Property(property="body", type="string", default="Updated blog content", description="Blog body content"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="published_at", type="string", format="date-time", default="2024-08-19T07:26:07.000000Z", description="Publication date"),
 *     @OA\Property(property="category_id", type="integer", default=1, description="Blog category ID"),
 *     @OA\Property(property="slug", type="string", default="updated-blog-slug", description="Blog slug/URL"),
 *     @OA\Property(property="seo_title", type="string", default="Updated SEO title", description="SEO title"),
 *     @OA\Property(property="seo_description", type="string", default="Updated SEO description", description="SEO description"),
 *     @OA\Property(property="canonical", type="string", default="https://example.com", description="Canonical URL"),
 *     @OA\Property(property="old_url", type="string", default="https://example.com/old", description="Old URL for redirect"),
 *     @OA\Property(property="redirect_to", type="string", default="https://example.com/new", description="Redirect target URL"),
 *     @OA\Property(property="robots_meta", type="string", default="index_follow", enum={"index_follow", "noindex_nofollow", "noindex_follow"}, description="Robots meta tag"),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"updated-tag1", "updated-tag2"}, description="Blog tags"),
 *     @OA\Property(property="image", type="string", format="binary", description="Blog featured image"),
 * )
 */
class UpdateBlogRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        $rules         =  (new StoreBlogRequest())->rules();
        $rules['slug'] = 'required|unique:blogs,slug,' . $this->route('blog')->id;

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
