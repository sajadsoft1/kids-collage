<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TagResource",
 *     title="TagResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="name", type="string", default="Laravel"),
 *     @OA\Property(property="slug", type="string", default="laravel"),
 * )
 */
class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
