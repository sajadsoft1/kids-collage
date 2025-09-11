<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateOpinionRequest",
 *     title="Update Opinion request",
 *     type="object",
 *     required={"user_name", "comment", "published"},
 *
 *     @OA\Property(property="user_name", type="string", default="Jane Smith", description="Opinion author name"),
 *     @OA\Property(property="comment", type="string", default="Outstanding service! Updated review.", description="Opinion comment/review"),
 *     @OA\Property(property="company", type="string", default="XYZ Corporation", description="Author's company"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="Opinion order"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 *     @OA\Property(property="image", type="string", format="binary", description="Author profile image"),
 * )
 */
class UpdateOpinionRequest extends StoreOpinionRequest {}
