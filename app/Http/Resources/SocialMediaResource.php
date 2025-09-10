<?php

namespace App\Http\Resources;

use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SocialMediaResource",
 *     title="SocialMediaResource",
 *     @OA\Property( property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="SocialMedia Title"),
 *     @OA\Property(property="description", type="string", default="SocialMedia Description"),
 *
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class SocialMediaResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'updated_at'  => $this->updated_at,
            'created_at'  => $this->created_at,
        ];
    }
}
