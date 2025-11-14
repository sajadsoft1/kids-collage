<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CommentDetailResource",
 *     title="CommentDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="comment", type="string", default="Great article!"),
 *     @OA\Property(property="published", type="string", default="active"),
 *     @OA\Property(property="rate", type="integer", default="5"),
 *     @OA\Property(property="suggest", type="string", default="yes"),
 *     @OA\Property(property="admin_note", type="string", default="Good feedback"),
 *     @OA\Property(property="languages", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="user", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="admin", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="parent", ref="#/components/schemas/SimpleCommentResource"),
 *     @OA\Property(property="children", type="array", @OA\Items(ref="#/components/schemas/SimpleCommentResource")),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class CommentDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = CommentResource::make($this)->toArray($request);

        return array_merge($resource, [
            'admin_note' => $this->admin_note,
            'languages' => $this->languages,
            'parent' => $this->whenLoaded('parent', fn () => SimpleCommentResource::make($this->parent)),
            'children' => $this->whenLoaded('children', fn () => SimpleCommentResource::collection($this->children)),
        ]);
    }
}
