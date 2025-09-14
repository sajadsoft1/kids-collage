<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\BannerSizeEnum;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreBannerRequest",
 *     title="Store Banner request",
 *     type="object",
 *     required={"title", "published","size",'image'},
 *
 *     @OA\Property(property="title", type="string", default="test title", description="Banner title"),
 *     @OA\Property(property="description", type="string", default="test description", description="Banner description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 *     @OA\Property(property="size", ref="#/components/schemas/BannerSizeEnum"),
 *     @OA\Property(property="image", type="string", format="binary", description="Banner image file"),
 * )
 */
class StoreBannerRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'published'    => 'required|boolean',
            'published_at' => 'nullable|date',
            'size'         => 'nullable|in:' . implode(',', BannerSizeEnum::values()),
            'image'        => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
