<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\YesNoEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class FaqResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="FaqResource",
     *     title="FaqResource",
     *     @OA\Property(property="id", type="integer", default="1"),
     *     @OA\Property(property="title", type="string", default="Faq Title"),
     *     @OA\Property(property="description", type="string", default="Faq Description"),
     *     @OA\Property(property="category", ref="#/components/schemas/CategoryResource"),
     *     @OA\Property(property="favorite", ref="#/components/schemas/YesNoEnum"),
     *     @OA\Property(property="ordering", type="integer", default="1"),
     *     @OA\Property(property="languages", type="array", @OA\Items(type="string"), default={"en", "fa"}),
     *     @OA\Property(property="like_count", type="integer", default="10"),
     *     @OA\Property(property="view_count", type="integer", default="100"),
     *     @OA\Property(property="published",  ref="#/components/schemas/YesNoEnum"),
     *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
     *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'category'    => $this->whenLoaded('category', fn () => CategoryResource::make($this->category)),
            'favorite'    => $this->favorite->toArray(),
            'ordering'    => $this->ordering,
            'languages'   => $this->languages,
            'like_count'  => $this->like_count,
            'view_count'  => $this->view_count,
            'published'   => $this->published->toArray(),
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
