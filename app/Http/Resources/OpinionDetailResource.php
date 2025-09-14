<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use App\Models\Opinion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="OpinionDetailResource",
 *     title="OpinionDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="ordering", type="integer", default=1),
 *     @OA\Property(property="view_count", type="integer", default=1),
 *     @OA\Property(property="company", type="string", default="Company"),
 *     @OA\Property(property="user_name", type="string", default="User Name"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="comment", type="string", default="Full opinion comment..."),
 * )
 */
class OpinionDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource      = OpinionResource::make($this)->toArray($request);
        $resource['id']=$this->id;
        $resource['comment']=$this->comment;
        $resource['image']= $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_1280_400);

        return $resource;
    }
}
