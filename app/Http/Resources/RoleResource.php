<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="RoleResource",
 *     title="RoleResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="name", type="string", default="Role name"),
 *     @OA\Property(property="description", type="string", default="Role Description"),
 *     @OA\Property(property="guard_name", type="string", default="web"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'       => $this->name,
            'guard_name'       => $this->guard_name,
            'description' => $this->description,
            'updated_at'  => $this->updated_at,
            'created_at'  => $this->created_at,
        ];
    }
}
