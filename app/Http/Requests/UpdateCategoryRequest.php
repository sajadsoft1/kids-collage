<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateCategoryRequest",
 *     title="Update Category request",
 *     type="object",
 *    required={"title", "published","slug" ,"type", "seo_title", "seo_description", "robots_meta"},
 *
 *     @OA\Property(property="title", type="string", default="Updated category title", description="Category title"),
 *     @OA\Property(property="description", type="string", default="Updated category description", description="Category description"),
 *     @OA\Property(property="body", type="string", default="Updated category body", description="Category body content"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="parent_id", type="integer", description="Parent category ID"),
 *     @OA\Property(property="type", type="string", enum={"blog", "portfolio", "faq"}, default="blog", description="Category type"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="Category order"),
 *     @OA\Property(property="slug", type="string", default="updated-slug", description="Category slug"),
 *     @OA\Property(property="seo_title", type="string", default="Updated SEO title", description="SEO title"),
 *     @OA\Property(property="seo_description", type="string", default="Updated SEO description", description="SEO description"),
 *     @OA\Property(property="canonical", type="string", default="https://example.com", description="Canonical URL"),
 *     @OA\Property(property="old_url", type="string", default="https://example.com/old", description="Old URL for redirect"),
 *     @OA\Property(property="redirect_to", type="string", default="https://example.com/new", description="Redirect target URL"),
 *     @OA\Property(property="robots_meta", type="string", default="index_follow", enum={"index_follow", "noindex_nofollow", "noindex_follow"}, description="Robots meta tag"),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"tag1", "tag2"}, description="Category tags"),
 *     @OA\Property(property="image", type="string", format="binary", description="Category image file"),
 * )
 */
class UpdateCategoryRequest extends FormRequest
{
    use FillAttributes;
    public function rules(): array
    {
        return (new StoreCategoryRequest())->rules();
    }
    protected function prepareForValidation()
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }

}
