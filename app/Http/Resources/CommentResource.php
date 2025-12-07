<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CommentResource",
 *     title="CommentResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="comment", type="string", default="Great article!"),
 *     @OA\Property(property="published", type="string", default="active"),
 *     @OA\Property(property="rate", type="integer", default="5"),
 *     @OA\Property(property="suggest", type="string", default="yes"),
 *     @OA\Property(property="user", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="admin", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="children_count", type="integer", default="3"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="children", type="array", @OA\Items(ref="#/components/schemas/SimpleCommentResource")),
 * )
 */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'published' => $this->published->value,
            'rate' => $this->rate,
            'suggest' => $this->suggest->value,
            'user' => $this->whenLoaded('user', fn () => SimpleUserResource::make($this->user)),
            'admin' => $this->whenLoaded('admin', fn () => SimpleUserResource::make($this->admin)),
            'children_count' => $this->whenLoaded('children', fn () => $this->children->count(), 0),
            'children' => $this->whenLoaded('children', fn () => SimpleCommentResource::collection($this->children)),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
