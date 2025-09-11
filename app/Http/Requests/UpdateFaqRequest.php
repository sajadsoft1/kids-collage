<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="UpdateFaqRequest",
 *      title="Update Faq request",
 *      type="object",
 *      required={"title", "description", "published", "category_id"},
 *
 *     @OA\Property(property="title", type="string", default="Updated FAQ Question", description="FAQ title"),
 *     @OA\Property(property="description", type="string", default="Updated answer to the question", description="FAQ answer/description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="favorite", type="boolean", default=false, description="Mark as favorite FAQ"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="FAQ order"),
 *     @OA\Property(property="category_id", type="integer", default=1, description="FAQ category ID"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 * )
 */
class UpdateFaqRequest extends StoreFaqRequest {}
