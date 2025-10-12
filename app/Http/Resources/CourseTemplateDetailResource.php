<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="CourseTemplateDetailResource",
 *     title="CourseTemplateDetailResource",
 *     @OA\Property( property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="description", type="string", default="Description"),
 *     @OA\Property(property="slug", type="string", default="title"),
 *     @OA\Property(property="level", ref="#/components/schemas/CourseLevelEnum"),
 *     @OA\Property(property="prerequisites", type="array", @OA\Items(type="string"), example={"Basic English"}),
 *     @OA\Property(property="is_self_paced", type="boolean", default=true),
 *     @OA\Property(property="type", ref="#/components/schemas/CourseTypeEnum"),
 *     @OA\Property(property="view_count", type="integer", default=0),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", default="http://localhost/storage/1/image/1280x720/filename.jpg"),
 *     @OA\Property(property="session_templates_count", type="integer", default=5),
 *     @OA\Property(property="body", type="string", default="Body content"),
 *     @OA\Property(property="comments", type="array", @OA\Items(ref="#/components/schemas/CommentResource")),
 *     @OA\Property(property="seo_option",type="object"),
 *     @OA\Property(property="courses", type="array", @OA\Items(ref="#/components/schemas/CourseResource")),
 *
 * )
 */
class CourseTemplateDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $resource = CourseTemplateResource::make($this)->toArray($request);

        return array_merge($resource, [
            'body'       => $this->body,
            'comments'   => $this->whenLoaded('comments', fn () => CommentResource::collection($this->comments)),
            'seo_option' => $this->seoOption,
            'image'      => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720),
            'courses'=>$this->whenLoaded('courses', fn()=>CourseResource::collection($this->activeCourses->load('term','teacher','sessions'))),

        ]);
    }
}
