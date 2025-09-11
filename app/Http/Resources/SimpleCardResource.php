<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SimpleCardResource",
 *     title="SimpleCardResource",
 *
 *     @OA\Property(property="id", type="integer", description="Card id", example="1"),
 *     @OA\Property(property="title", type="string", example="Fix login bug"),
 *     @OA\Property(property="card_type", type="string", example="task"),
 *     @OA\Property(property="priority", type="string", example="high"),
 *     @OA\Property(property="status", type="string", example="in_progress"),
 * )
 */
class SimpleCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'card_type' => $this->card_type->value,
            'priority'  => $this->priority->value,
            'status'    => $this->status->value,
        ];
    }
}
