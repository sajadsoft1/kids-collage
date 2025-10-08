<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="OpinionDetailResource",
 *     title="OpinionDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="description", type="string", default="Description"),
 *     @OA\Property(property="comment", type="string", default="comment somethin ....."),
 *     @OA\Property(property="user_name", type="string", default="ahmad dehestani"),
 *     @OA\Property(property="company", type="string", default="karno web"),
 *     @OA\Property(property="ordering", type="integer", default=1),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 * )
 */
class OpinionDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return OpinionResource::make($this)->toArray($request);
    }
}
