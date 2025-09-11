<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PortfolioDetailResource",
 *     title="PortfolioDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Website Redesign"),
 *     @OA\Property(property="description", type="string", default="Modern responsive design"),
 *     @OA\Property(property="body", type="string", default="Full portfolio content..."),
 *     @OA\Property(property="published", type="string", default="active"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="execution_date", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="languages", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="category", ref="#/components/schemas/SimpleCategoryResource"),
 *     @OA\Property(property="creator", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="tags", type="array", @OA\Items(ref="#/components/schemas/SimpleTagResource")),
 *     @OA\Property(property="seo_option", type="object"),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="view_count", type="integer", default="150"),
 *     @OA\Property(property="like_count", type="integer", default="25"),
 *     @OA\Property(property="wish_count", type="integer", default="10"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class PortfolioDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = PortfolioResource::make($this)->toArray($request);

        return array_merge($resource, [
            'body'       => $this->body,
            'languages'  => $this->languages,
            'seo_option' => $this->whenLoaded('seoOption', fn () => $this->seoOption),
            'image'      => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE),
        ]);
    }
}
