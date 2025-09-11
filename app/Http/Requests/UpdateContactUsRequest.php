<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateContactUsRequest",
 *     title="Update ContactUs request",
 *     type="object",
 *     required={"name", "email", "mobile", "comment"},
 *
 *     @OA\Property(property="name", type="string", default="Jane Doe", description="Contact person name"),
 *     @OA\Property(property="email", type="string", format="email", default="jane@example.com", description="Contact email address"),
 *     @OA\Property(property="mobile", type="string", default="09123456789", description="Contact mobile number"),
 *     @OA\Property(property="comment", type="string", default="Updated message content", description="Contact message/comment"),
 * )
 */
class UpdateContactUsRequest extends StoreContactUsRequest {}
