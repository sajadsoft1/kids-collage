<?php

namespace App\Http\Resources;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BoardDetailResource",
 *     title="BoardDetailResource",
 *     @OA\Property( property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Board Title"),
 *     @OA\Property(property="description", type="string", default="Board Description"),
 *
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class BoardDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $resource = BoardResource::make($this)->toArray($request);
        $resource['id']=$this->id;

        return $resource;
    }
}
