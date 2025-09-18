<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BranchResource",
 *     title="BranchResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Branch Title"),
 *     @OA\Property(property="description", type="string", default="Branch Description"),
 *     @OA\Property(property="address", type="string", default="Branch Address"),
 *     @OA\Property(property="phone", type="string", default="+1234567890"),
 *     @OA\Property(property="extra_attributes", type="object",
 *         @OA\Property(property="key1", type="string", default="value1"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class BranchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'description'      => $this->description,
            'address'          => $this->address,
            'phone'            => $this->phone,
            'extra_attributes' => $this->extra_attributes,
            'published'      => $this->published->toArray(),
            'updated_at'       => $this->updated_at,
            'created_at'       => $this->created_at,
        ];
    }
}
