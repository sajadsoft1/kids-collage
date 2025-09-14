<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ContactUsResource",
 *     title="ContactUsResource",
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
class ContactUsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'       => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'comment' => $this->comment,
            'admin_note' => $this->admin_note,
            'follow_up' => $this->follow_up->toArray(),
            'updated_at'  => $this->updated_at,
            'created_at'  => $this->created_at,
        ];
    }
}
