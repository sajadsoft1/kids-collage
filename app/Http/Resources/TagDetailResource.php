<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TagDetailResource",
 *     title="TagDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="name", type="string", default="Laravel"),
 *     @OA\Property(property="slug", type="string", default="laravel"),
 *     @OA\Property(property="description", type="string", default="PHP framework"),
 *     @OA\Property(property="body", type="string", default="Detailed tag content..."),
 *     @OA\Property(property="type", type="string", default="blog"),
 *     @OA\Property(property="order_column", type="integer", default="1"),
 *     @OA\Property(property="languages", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="seo_option", type="object"),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="is_in_use", type="boolean", default=true),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class TagDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = TagResource::make($this)->toArray($request);

        return array_merge($resource, [
            'body'       => $this->body,
            'languages'  => $this->languages,
            'seo_option' =>  $this->seoOption,
        ]);
    }
}
