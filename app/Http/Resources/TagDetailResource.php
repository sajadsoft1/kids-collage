<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
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
 *     @OA\Property(property="body", type="string", default="Body content"),
 *     @OA\Property(property="seo_option", type="object"),
 *     @OA\Property(property="image", type="string", default="http://localhost/storage/1/image/720x720/filename.jpg"),
 *
 * )
 */
class TagDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = TagResource::make($this)->toArray($request);

        return array_merge($resource, [
            'body' => $this->body,
            'seo_option' => $this->seoOption,
            'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE),
        ]);
    }
}
