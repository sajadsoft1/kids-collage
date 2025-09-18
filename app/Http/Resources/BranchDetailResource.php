<?php

namespace App\Http\Resources;

use App\Helpers\Constants;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BranchDetailResource",
 *     title="BranchDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Branch Title"),
 *     @OA\Property(property="description", type="string", default="Branch Description"),
 *     @OA\Property(property="address", type="string", default="Branch Address"),
 *     @OA\Property(property="phone", type="string", default="+1234567890"),
 *     @OA\Property(property="extra_attributes", type="object",
 *         @OA\Property(property="key1", type="string", default="value1"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", format="url", default="http://example.com/image.jpg"),
 * )
 */
class BranchDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $resource = BranchResource::make($this)->toArray($request);
        $resource['id']=$this->id;
        $resource['image']=$this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720);

        return $resource;
    }
}
