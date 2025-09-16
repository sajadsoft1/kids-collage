<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateFaqRequest",
 *     title="Update Faq request",
 *     type="object",
 *     required={"title", "description", "published", "category_id", "favorite"},
 *
 *     @OA\Property(property="title", type="string", default="Frequently Asked Question", description="FAQ title"),
 *     @OA\Property(property="description", type="string", default="This is the answer to the question", description="FAQ answer/description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="favorite", type="boolean", default=false, description="Is favorite FAQ"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="FAQ order"),
 *     @OA\Property(property="category_id", type="integer", default=1, description="FAQ category ID"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 * )
 */
class UpdateFaqRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return (new StoreFaqRequest)->rules();
    }

    protected function prepareForValidation()
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
            'favorite'  => request('favorite', false),
        ]);
    }
}
