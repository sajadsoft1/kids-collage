<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserDetailResource",
 *     title="UserDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="name", type="string", default="John"),
 *     @OA\Property(property="family", type="string", default="Doe"),
 *     @OA\Property(property="email", type="string", default="john@example.com"),
 *     @OA\Property(property="mobile", type="string", default="09123456789"),
 *     @OA\Property(property="gender", type="string", default="male"),
 *     @OA\Property(property="status", type="string", default="active"),
 *     @OA\Property(property="email_verified_at", type="string", nullable=true),
 *     @OA\Property(property="mobile_verified_at", type="string", nullable=true),
 *     @OA\Property(property="avatar", type="string", default="https://example.com/avatar.jpg"),
 *     @OA\Property(property="roles", type="array", @OA\Items(type="object")),
 *     @OA\Property(property="permissions", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="blogs_count", type="integer", default="5"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class UserDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = UserResource::make($this)->toArray($request);

        return array_merge($resource, [
            'email'              => $this->email,
            'gender'             => $this->gender?->value,
            'email_verified_at'  => $this->email_verified_at,
            'mobile_verified_at' => $this->mobile_verified_at,
            'permissions'        => $this->whenLoaded('permissions', fn () => $this->getAllPermissions()->pluck('name')),
            'blogs_count'        => $this->whenLoaded('blogs', fn () => $this->blogs->count(), 0),
        ]);
    }
}
