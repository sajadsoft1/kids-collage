<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ContactUsDetailResource",
 *     title="ContactUsDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="name", type="string", default="John Doe"),
 *     @OA\Property(property="email", type="string", default="info@example.com"),
 *     @OA\Property(property="mobile", type="string", default="+1234567890"),
 *     @OA\Property(property="comment", type="string", default="This is a comment."),
 *     @OA\Property(property="admin_note", type="string", default="This is an admin note."),
 *     @OA\Property(property="follow_up", ref="#/components/schemas/YesNoEnum"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class ContactUsDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource      = ContactUsResource::make($this)->toArray($request);
        $resource['id']=$this->id;

        return $resource;
    }
}
