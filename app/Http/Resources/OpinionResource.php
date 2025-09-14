<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Opinion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="OpinionResource",
 *     title="OpinionResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="published", type="boolean", default="true"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="ordering", type="integer", default=1),
 *     @OA\Property(property="view_count", type="integer", default=1),
 *     @OA\Property(property="company", type="string", default="Company"),
 *     @OA\Property(property="user_name", type="string", default="User Name"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class OpinionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'published'       => $this->published,
            'published_at'       => $this->published_at,
            'ordering'       => $this->ordering,
            'view_count'       => $this->view_count,
            'company'       => $this->company,
            'user_name' => $this->user_name,
            'updated_at'  => $this->updated_at,
            'created_at'  => $this->created_at,
        ];
    }
}
