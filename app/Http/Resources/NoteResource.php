<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="NoteResource",
 *     title="NoteResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="body", type="string", default="body ...."),
 *     @OA\Property(property="question", type="array", @OA\Items(ref="#/components/schemas/QuestionResource")),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class NoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'question' => $this->whenLoaded('question', function () {
                return QuestionResource::make($this->resource->question);
            }),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
