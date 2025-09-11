<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateBoardRequest",
 *     title="Update Board request",
 *     type="object",
 *     required={"title"},
 *
 *     @OA\Property(property="title", type="string", default="Updated board title", description="Board title"),
 *     @OA\Property(property="description", type="string", default="Updated board description", description="Board description"),
 * )
 */
class UpdateBoardRequest extends StoreBoardRequest {}
