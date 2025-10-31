<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CourseResource",
 *     title="CourseResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="price", type="number", format="float", default="0.00"),
 *     @OA\Property(property="capacity", type="integer", default="30"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="enrollments_count", type="integer", default="10"),
 *     @OA\Property(property="sessions_count", type="integer", default="5"),
 *     @OA\Property(property="term", ref="#/components/schemas/TermResource"),
 *     @OA\Property(property="teacher", ref="#/components/schemas/UserResource"),
 * )
 */
class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'price'             => $this->price,
            'capacity'          => $this->capacity,
            'updated_at'        => $this->updated_at,
            'created_at'        => $this->created_at,
            'enrollments_count' => $this->enrollments()->count(),
            'sessions_count'    => $this->sessions()->count(),
            'term'              => $this->whenLoaded('term', function () {
                return TermResource::make($this->term);
            }),
            'teacher'           => $this->whenLoaded('teacher', function () {
                return UserResource::make($this->teacher);
            }),
        ];
    }
}
