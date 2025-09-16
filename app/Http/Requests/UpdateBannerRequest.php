<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateBannerRequest",
 *     title="Update Banner request",
 *     type="object",
 *     required={"title", "published", "size", "image"},
 *     @OA\Property(property="title", type="string", default="test title", description="Banner title"),
 *     @OA\Property(property="description", type="string", default="test description", description="Banner description"),
 *     @OA\Property(property="size", type="string", enum={"1x1", "16x9", "4x3"}, default="1x1", description="Banner size"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 *     @OA\Property(property="image", type="string", format="binary", description="Banner image file"),
 * )
 */
class UpdateBannerRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return (new StoreBannerRequest)->rules();
    }

    protected function prepareForValidation()
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
