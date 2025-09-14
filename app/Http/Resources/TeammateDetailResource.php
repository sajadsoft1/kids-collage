<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Teammate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TeammateDetailResource",
 *     title="TeammateDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="John Doe"),
 *     @OA\Property(property="description", type="string", default="Senior Developer"),
 *     @OA\Property(property="position", type="string", default="Senior Developer"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="birthday", type="string", nullable=true),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class TeammateDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource      = TeammateResource::make($this)->toArray($request);
        $resource['id']=$this->id;

        return $resource;
    }
}
