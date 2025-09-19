<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ClassroomResource",
 *     title="ClassroomResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Classroom Title"),
 *     @OA\Property(property="description", type="string", default="Classroom Description"),
 *     @OA\Property(property="capacity", type="integer", default="30"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="branch", ref="#/components/schemas/BranchResource"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class ClassroomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'capacity'    => $this->capacity,
            'published'   => $this->published->toArray(),
            'branch'      => $this->whenLoaded('branch', fn () => BranchResource::make($this->branch)),
            'updated_at'  => $this->updated_at,
            'created_at'  => $this->created_at,
        ];
    }
}
