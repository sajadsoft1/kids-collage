<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="UpdateCardFlowRequest",
 *      title="Update CardFlow request",
 *      type="object",
 *      required={"title"},
 *
 *     @OA\Property(property="title", type="string", default="Updated cardflow title", description="CardFlow title"),
 *     @OA\Property(property="description", type="string", default="Updated cardflow description", description="CardFlow description"),
 * )
 */
class UpdateCardFlowRequest extends StoreCardFlowRequest {}
