<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BoardResource",
 *     title="BoardResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="name", type="string", default="Project Alpha"),
 *     @OA\Property(property="description", type="string", default="Main project board"),
 *     @OA\Property(property="color", type="string", default="#FF5733"),
 *     @OA\Property(property="is_active", type="boolean", default=true),
 *     @OA\Property(property="system_protected", type="boolean", default=false),
 *     @OA\Property(property="users_count", type="integer", default="5"),
 *     @OA\Property(property="cards_count", type="integer", default="25"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class BoardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'description'      => $this->description,
            'color'            => $this->color,
            'is_active'        => $this->is_active,
            'system_protected' => $this->system_protected,
            'users_count'      => $this->whenLoaded('users', fn () => $this->users->count(), 0),
            'cards_count'      => $this->whenLoaded('cards', fn () => $this->cards->count(), 0),
            'updated_at'       => $this->updated_at,
            'created_at'       => $this->created_at,
        ];
    }
}
