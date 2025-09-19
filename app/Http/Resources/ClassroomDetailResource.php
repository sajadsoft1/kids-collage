<?php

namespace App\Http\Resources;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ClassroomDetailResource",
 *     title="ClassroomDetailResource",
 *     @OA\Property( property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Classroom Title"),
 *     @OA\Property(property="description", type="string", default="Classroom Description"),
 *
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class ClassroomDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $resource = ClassroomResource::make($this)->toArray($request);
        $resource['id']=$this->id;

        return $resource;
    }
}
