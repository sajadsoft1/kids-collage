<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateTeammateRequest",
 *     title="Update Teammate request",
 *     type="object",
 *     required={"title", "position", "published"},
 *
 *     @OA\Property(property="title", type="string", default="Jane Smith", description="Teammate name"),
 *     @OA\Property(property="description", type="string", default="Updated team member description", description="Teammate description"),
 *     @OA\Property(property="bio", type="string", default="Updated team member biography", description="Teammate biography"),
 *     @OA\Property(property="position", type="string", default="Senior Developer", description="Teammate position"),
 *     @OA\Property(property="birthday", type="string", format="date", default="1990-01-01", description="Teammate birthday"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="email", type="string", format="email", default="jane@example.com", description="Teammate email"),
 *     @OA\Property(property="image", type="string", format="binary", description="Teammate profile image"),
 *     @OA\Property(property="bio_image", type="string", format="binary", description="Teammate bio image"),
 * )
 */
class UpdateTeammateRequest extends StoreTeammateRequest {}
