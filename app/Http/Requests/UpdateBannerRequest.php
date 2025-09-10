<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="UpdateBannerRequest",
 *      title="Update Banner request",
 *      type="object",
 *      required={"title"},
 *
 *     @OA\Property(property="title", type="string", default="test title updated"),
 *     @OA\Property(property="description", type="string", default="test description updated"),
 * )
 */
class UpdateBannerRequest extends StoreBannerRequest {}
