<?php

namespace App\Http\Resources;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CardDetailResource",
 *     title="CardDetailResource",
 *     @OA\Property( property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Card Title"),
 *     @OA\Property(property="description", type="string", default="Card Description"),
 *
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class CardDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $resource = CardResource::make($this)->toArray($request);
        $resource['id']=$this->id;

        return $resource;
    }
}
