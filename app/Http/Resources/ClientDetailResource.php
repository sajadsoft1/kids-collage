<?php

namespace App\Http\Resources;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ClientDetailResource",
 *     title="ClientDetailResource",
 *     @OA\Property( property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Client Title"),
 *     @OA\Property(property="description", type="string", default="Client Description"),
 *
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class ClientDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $resource = ClientResource::make($this)->toArray($request);
        $resource['id']=$this->id;

        return $resource;
    }
}
