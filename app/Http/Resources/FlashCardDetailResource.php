<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="FlashCardDetailResource",
 *     title="FlashCardDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="fornt", type="string", default="front content"),
 *     @OA\Property(property="back", type="string", default="back content"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class FlashCardDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = FlashCardResource::make($this)->toArray($request);

        return array_merge($resource, [
        ]);
    }
}
