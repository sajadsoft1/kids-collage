<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\SocialMediaPositionEnum;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreSocialMediaRequest",
 *     title="Store SocialMedia request",
 *     type="object",
 *     required={"title", "link", "position", "published", "ordering"},
 *
 *     @OA\Property(property="title", type="string", default="Facebook", description="Social media platform title"),
 *     @OA\Property(property="link", type="string", default="https://facebook.com/username", description="Social media profile URL"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="Display order"),
 *     @OA\Property(property="position", ref="#/components/schemas/SocialMediaPositionEnum"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="image", type="string", format="binary", description="Social media icon/image"),
 * )
 */
class StoreSocialMediaRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title'     => ['required', 'string', 'max:255'],
            'link'      => ['required', 'url'],
            'ordering'  => 'required|integer|min:0',
            'position'  => 'required|in:' . implode(',', SocialMediaPositionEnum::values()),
            'published' => 'required|boolean',
            'image'     => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
