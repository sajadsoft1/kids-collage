<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BannerDetailResource",
 *     title="BannerDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Banner Title"),
 *     @OA\Property(property="description", type="string", default="Banner Description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="size", ref="#/components/schemas/BannerSizeEnum"),
 *     @OA\Property(property="click", type="int", default=1),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", format="url", example="/media/1/conversions/100_square.jpg"),
 * )
 */
class BannerDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource      = BannerResource::make($this)->toArray($request);
        $resource['id']=$this->id;
        $resource['image']=  $this->resource->getFirstMediaUrl('image',$this->resource->getPrimaryResolution());

        return $resource;
    }
}
