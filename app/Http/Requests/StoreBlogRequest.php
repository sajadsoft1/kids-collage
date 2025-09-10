<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\SeoRobotsMetaEnum;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreBlogRequest",
 *     title="Store Blog request",
 *     type="object",
 *     required={"title", "description", "published", "category_id", "slug", "seo_title", "seo_description", "robots_meta"},
 *
 *     @OA\Property(property="title", type="string", default="test title"),
 *     @OA\Property(property="description", type="string", default="test description"),
 *     @OA\Property(property="body", type="string", default="test body"),
 *     @OA\Property(property="published", type="boolean", default=true),
 *     @OA\Property(property="published_at", type="string", format="date-time", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="category_id", type="integer", default=1),
 *     @OA\Property(property="slug", type="string", default="test-slug"),
 *     @OA\Property(property="seo_title", type="string", default="test seo title"),
 *     @OA\Property(property="seo_description", type="string", default="test seo description"),
 *     @OA\Property(property="canonical", type="string", default="https://example.com"),
 *     @OA\Property(property="old_url", type="string", default="https://example.com/old"),
 *     @OA\Property(property="redirect_to", type="string", default="https://example.com/new"),
 *     @OA\Property(property="robots_meta", type="string", default="index_follow",enum={"index_follow","noindex_nofollow","noindex_follow"}),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"tag1", "tag2"}),
 *     @OA\Property(property="image", type="string", format="binary"),
 * )
 */
class StoreBlogRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string'],
            'body'            => ['nullable', 'string'],
            'published'       => 'required|boolean',
            'published_at'    => 'nullable|date',
            'category_id'     => 'required|exists:categories,id',
            'slug'            => 'required|unique:blogs,slug',
            'seo_title'       => 'required|string|max:255',
            'seo_description' => 'required|string|max:255',
            'canonical'       => 'nullable|url',
            'old_url'         => 'nullable|url',
            'redirect_to'     => 'nullable|url',
            'robots_meta'     => 'required|in:'.implode(',', SeoRobotsMetaEnum::values()),
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
