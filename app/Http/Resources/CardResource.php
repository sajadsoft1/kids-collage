<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CardResource",
 *     title="CardResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Fix login bug"),
 *     @OA\Property(property="description", type="string", default="Bug in login form"),
 *     @OA\Property(property="card_type", type="string", default="task"),
 *     @OA\Property(property="priority", type="string", default="high"),
 *     @OA\Property(property="status", type="string", default="in_progress"),
 *     @OA\Property(property="due_date", type="string", nullable=true),
 *     @OA\Property(property="order", type="integer", default="1"),
 *     @OA\Property(property="board", ref="#/components/schemas/SimpleBoardResource"),
 *     @OA\Property(property="column", type="object"),
 *     @OA\Property(property="assignees_count", type="integer", default="2"),
 *     @OA\Property(property="is_overdue", type="boolean", default=false),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class CardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'description'      => $this->description,
            'card_type'        => $this->card_type->value,
            'priority'         => $this->priority->value,
            'status'           => $this->status->value,
            'due_date'         => $this->due_date,
            'order'            => $this->order,
            'board'            => $this->whenLoaded('board', fn () => SimpleBoardResource::make($this->board)),
            'column'           => $this->whenLoaded('column', fn () => $this->column),
            'assignees_count'  => $this->whenLoaded('assignees', fn () => $this->assignees->count(), 0),
            'is_overdue'       => $this->isOverdue(),
            'updated_at'       => $this->updated_at,
            'created_at'       => $this->created_at,
        ];
    }
}
