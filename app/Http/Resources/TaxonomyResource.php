<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="TaxonomyResource",
 *     title="TaxonomyResource",
 *     @OA\Property( property="id", type="integer", default="1"),
 *     @OA\Property(property="name", type="string", default="test"),
 *     @OA\Property(property="color", type="string", default="$ffffff"),
 * )
 */
class TaxonomyResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'       => $this->name,
            'color' => $this->color,

        ];
    }
}
