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
 *     @OA\Property(property="title", type="string", default="Opinion Title"),
 *     @OA\Property(property="description", type="string", default="Opinion Description"),
 *
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
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
