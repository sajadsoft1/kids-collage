<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\UserTypeEnum;
use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="UserResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="name", type="string", default="User Name"),
 *     @OA\Property(property="family", type="string", default="User Family"),
 *     @OA\Property(property="mobile", type="string", default="09123456789"),
 *     @OA\Property(property="email", type="string", default="user@example.com"),
 *     @OA\Property(property="status", type="string", default="active"),
 *     @OA\Property(property="type", ref="#/components/schemas/UserTypeEnum"),
 *     @OA\Property(property="avatar", type="string", default="https://example.com/avatar.jpg"),
 *     @OA\Property(property="roles", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'family' => $this->family,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'status' => $this->status->value,
            'type' => $this->type->toArray(),
            'avatar' => $this->resource->getFirstMediaUrl('avatar', Constants::RESOLUTION_100_SQUARE),
            'roles' => $this->whenLoaded('roles', fn () => $this->roles->pluck('name')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
