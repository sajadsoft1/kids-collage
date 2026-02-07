<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CourseTemplateResource",
 *     title="CourseTemplateResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="description", type="string", default="Description"),
 *     @OA\Property(property="slug", type="string", default="title"),
 *     @OA\Property(property="level", type="object", description="Course level (id, title)"),
 *     @OA\Property(property="prerequisites", type="array", @OA\Items(type="string"), example={"Basic English"}),
 *     @OA\Property(property="is_self_paced", type="boolean", default=true),
 *     @OA\Property(property="type", ref="#/components/schemas/CourseTypeEnum"),
 *     @OA\Property(property="view_count", type="integer", default=0),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", default="http://localhost/storage/1/image/1280x720/filename.jpg"),
 *     @OA\Property(property="session_templates_count", type="integer", default=5),
 * )
 */
class CourseTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'level' => $this->whenLoaded('level', fn () => [
                'id' => $this->level->id,
                'title' => $this->level->title,
            ]),
            'prerequisites' => $this->prerequisites,
            'is_self_paced' => $this->is_self_paced,
            'type' => $this->type->toArray(),
            'view_count' => $this->view_count,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_854_480),
            'session_count' => $this->sessionTemplates()->count(),
        ];
    }
}
