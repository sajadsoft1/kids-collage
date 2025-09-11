<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SimpleBoardResource",
 *     title="SimpleBoardResource",
 *
 *     @OA\Property(property="id", type="integer", description="Board id", example="1"),
 *     @OA\Property(property="name", type="string", example="Project Alpha"),
 *     @OA\Property(property="color", type="string", example="#FF5733"),
 *     @OA\Property(property="is_active", type="boolean", example="true"),
 * )
 */
class SimpleBoardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'color'     => $this->color,
            'is_active' => $this->is_active,
        ];
    }
}
