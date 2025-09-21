<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SimpleCommentResource",
 *     title="SimpleCommentResource",
 *
 *     @OA\Property(property="id", type="integer", description="Comment id", example="1"),
 *     @OA\Property(property="comment", type="string", example="Great article!", nullable=true),
 *     @OA\Property(property="rate", type="integer", example="5", nullable=true),
 *     @OA\Property(property="user", ref="#/components/schemas/SimpleUserResource"),
 * )
 */
class SimpleCommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'comment' => $this->comment,
            'rate'    => $this->rate,
            'user'    => $this->whenLoaded('user', fn () => SimpleUserResource::make($this->user)),
        ];
    }
}
