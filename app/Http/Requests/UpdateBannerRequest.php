<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateBannerRequest",
 *     title="Update Banner request",
 *     type="object",
 *     required={"title", "published"},
 *
 *     @OA\Property(property="title", type="string", default="Updated banner title", description="Banner title"),
 *     @OA\Property(property="description", type="string", default="Updated banner description", description="Banner description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 *     @OA\Property(property="size", ref="#/components/schemas/BannerSizeEnum"),
 *     @OA\Property(property="image", type="string", format="binary", description="Banner image file"),
 * )
 */
class UpdateBannerRequest extends StoreBannerRequest {}
