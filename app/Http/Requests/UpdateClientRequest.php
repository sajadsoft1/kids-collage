<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateClientRequest",
 *     title="Update Client request",
 *     type="object",
 *     required={"title", "published"},
 *
 *     @OA\Property(property="title", type="string", default="Updated client title", description="Client title"),
 *     @OA\Property(property="description", type="string", default="Updated client description", description="Client description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="link", type="string", default="https://example.com", description="Client website link"),
 *     @OA\Property(property="image", type="string", format="binary", description="Client logo/image file"),
 * )
 */
class UpdateClientRequest extends StoreClientRequest {}
