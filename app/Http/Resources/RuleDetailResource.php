<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="RuleDetailResource",
 *     title="RuleDetailResource",
 *     @OA\Property( property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="body", type="string", default="Body"),
 *     @OA\Property(property="slug", type="string", default="slug"),
 * )
 */
class RuleDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [

            'title'       => $this->title,
            'body' => $this->body,
            'slug' => $this->slug,

        ];
    }
}
