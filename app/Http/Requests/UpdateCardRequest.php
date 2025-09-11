<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="UpdateCardRequest",
 *      title="Update Card request",
 *      type="object",
 *      required={"title"},
 *
 *     @OA\Property(property="title", type="string", default="Updated card title", description="Card title"),
 *     @OA\Property(property="description", type="string", default="Updated card description", description="Card description"),
 * )
 */
class UpdateCardRequest extends StoreCardRequest {}
