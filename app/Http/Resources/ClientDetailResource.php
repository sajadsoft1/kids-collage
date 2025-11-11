<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ClientDetailResource",
 *     title="ClientDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="link", type="string", default="https://www.google.com"),
 *     @OA\Property(property="published",  ref="#/components/schemas/YesNoEnum"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="seo_option", type="object"),
 * )
 */
class ClientDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = ClientResource::make($this)->toArray($request);

        return array_merge($resource, [
            'seo_option' => $this->seoOption,
        ]);
    }
}
