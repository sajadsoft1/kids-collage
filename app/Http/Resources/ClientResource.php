<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ClientResource",
 *     title="ClientResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Client Title"),
 *     @OA\Property(property="description", type="string", default="Client Description"),
 *     @OA\Property(property="link", type="string", default="https://example.com"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", default="https://example.com/storage/clients/image.jpg"),
 * )
 */
class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'link'        => $this->link,
            'published'   => $this->published->toArray(),
            'updated_at'  => $this->updated_at,
            'created_at'  => $this->created_at,
            'image'       => $this->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE),
        ];
    }
}
