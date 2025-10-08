<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="LicenseDetailResource",
 *     title="LicenseDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *    @OA\Property(property="title", type="string", default="Title"),
 *    @OA\Property(property="description", type="string", default="Description"),
 *    @OA\Property(property="slug", type="string", default="blog-title"),
 *    @OA\Property(property="view_count", type="integer", default=100),
 *    @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *    @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *    @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *    @OA\Property(property="seo_option", type="object"),
 *)
 */
class LicenseDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $resource = LicenseResource::make($this)->toArray($request);

        return array_merge($resource, [
            'seo_option' => $this->seoOption,
            'image'      => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720),
        ]);
    }
}
