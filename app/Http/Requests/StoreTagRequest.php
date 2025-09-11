<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\SeoRobotsMetaEnum;
use App\Enums\TagTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreTagRequest",
 *     title="Store Tag request",
 *     type="object",
 *     required={"name", "seo_title", "seo_description", "robots_meta"},
 *
 *     @OA\Property(property="name", type="string", default="web-development", description="Tag name"),
 *     @OA\Property(property="description", type="string", default="Web development services", description="Tag description"),
 *     @OA\Property(property="body", type="string", default="<p>Detailed tag content...</p>", description="Tag detailed content"),
 *     @OA\Property(property="type", type="string", enum={"special"}, description="Tag type"),
 *     @OA\Property(property="order_column", type="integer", default=1, description="Tag order"),
 *     @OA\Property(property="seo_title", type="string", default="Web Development | Company Name", description="SEO title"),
 *     @OA\Property(property="seo_description", type="string", default="Professional web development services", description="SEO description"),
 *     @OA\Property(property="canonical", type="string", default="https://example.com/tags/web-development", description="Canonical URL"),
 *     @OA\Property(property="old_url", type="string", description="Old URL for redirect"),
 *     @OA\Property(property="redirect_to", type="string", description="Redirect target URL"),
 *     @OA\Property(property="robots_meta", type="string", default="index_follow", enum={"index_follow", "noindex_nofollow", "noindex_follow"}, description="Robots meta tag"),
 *     @OA\Property(property="image", type="string", format="binary", description="Tag featured image"),
 * )
 */
class StoreTagRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'body'            => ['nullable', 'string'],
            'type'            => 'nullable|in:' . implode(',', TagTypeEnum::values()),
            'order_column'    => 'nullable|integer|min:1',
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
        // Tag doesn't use published field based on action
    }
}
