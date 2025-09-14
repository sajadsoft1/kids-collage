<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="RoleDetailResource",
 *     title="RoleDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 * *     @OA\Property(property="name", type="string", default="Role name"),
 * *     @OA\Property(property="description", type="string", default="Role Description"),
 * *     @OA\Property(property="guard_name", type="string", default="web"),
 * *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z")
 * )
 */
class RoleDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource      = RoleResource::make($this)->toArray($request);
        $resource['id']=$this->id;

        return $resource;
    }
}
