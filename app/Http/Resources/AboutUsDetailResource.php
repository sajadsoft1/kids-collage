<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AboutUsDetailResource",
 *     title="AboutUsDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="body", type="string", default="Description"),
 *     @OA\Property(property="slug", type="string", default="title"),
 *     @OA\Property(property="seo_option", type="object"),
 *
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 * )
 */
class AboutUsDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'slug' => $this->slug,
            'seo_option' => $this->seoOption,
            'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720),
        ];
    }
}
