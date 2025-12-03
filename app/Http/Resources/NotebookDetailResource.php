<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="NotebookDetailResource",
 *     title="NotebookDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="body", type="string", default="body ...."),
 *     @OA\Property(property="taxonomy", type="array", @OA\Items(ref="#/components/schemas/TaxonomyResource")),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class NotebookDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = NotebookResource::make($this)->toArray($request);

        return array_merge($resource, [
        ]);
    }
}
