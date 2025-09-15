<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\CategoryTypeEnum;
use App\Enums\SeoRobotsMetaEnum;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreCategoryRequest",
 *     title="Store Category request",
 *     type="object",
 *     required={"title", "published","slug" ,"type", "seo_title", "seo_description", "robots_meta"},
 *
 *     @OA\Property(property="title", type="string", default="test title", description="Category title"),
 *     @OA\Property(property="description", type="string", default="test description", description="Category description"),
 *     @OA\Property(property="body", type="string", default="test body", description="Category body content"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="parent_id", type="integer", description="Parent category ID"),
 *     @OA\Property(property="type", ref="#/components/schemas/CategoryTypeEnum"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="Category order"),
 *     @OA\Property(property="slug", type="string", default="test-slug", description="Category slug"),
 *     @OA\Property(property="seo_title", type="string", default="test seo title", description="SEO title"),
 *     @OA\Property(property="seo_description", type="string", default="test seo description", description="SEO description"),
 *     @OA\Property(property="canonical", type="string", default="https://example.com", description="Canonical URL"),
 *     @OA\Property(property="old_url", type="string", default="https://example.com/old", description="Old URL for redirect"),
 *     @OA\Property(property="redirect_to", type="string", default="https://example.com/new", description="Redirect target URL"),
 *     @OA\Property(property="robots_meta", type="string", default="index_follow", enum={"index_follow", "noindex_nofollow", "noindex_follow"}, description="Robots meta tag"),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"tag1", "tag2"}, description="Category tags"),
 *     @OA\Property(property="image", type="string", format="binary", description="Category image file"),
 * )
 */
class StoreCategoryRequest extends FormRequest
{
    use FillAttributes;


    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'body'            => ['nullable', 'string'],
            'published'       => 'required|boolean',
            'parent_id'       => 'nullable|exists:categories,id',
            'type'            => 'required|in:' . implode(',', CategoryTypeEnum::values()),
            'ordering'        => 'nullable|integer|min:0',
            'slug'            => 'required|unique:categories,slug',
            'seo_title'       => 'required|string|max:255',
            'seo_description' => 'required|string|max:255',
            'canonical'       => 'nullable|url',
            'old_url'         => 'nullable|url',
            'redirect_to'     => 'nullable|url',
            'robots_meta'     => 'required|in:' . implode(',', SeoRobotsMetaEnum::values()),
            'tags'            => 'nullable|array',
            'image'           => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);

        $this->convertStringToArray([
            'tags' => request('tags', []),
        ]);
    }
}
