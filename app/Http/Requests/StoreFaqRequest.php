<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreFaqRequest",
 *     title="Store Faq request",
 *     type="object",
 *     required={"title", "description", "published", "category_id","favorite"},
 *
 *     @OA\Property(property="title", type="string", default="Frequently Asked Question", description="FAQ title"),
 *     @OA\Property(property="description", type="string", default="This is the answer to the question", description="FAQ answer/description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="favorite", type="boolean", default=false, description="Mark as favorite FAQ"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="FAQ order"),
 *     @OA\Property(property="category_id", type="integer", default=1, description="FAQ category ID"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 * )
 */
class StoreFaqRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'published'    => 'required|boolean',
            'favorite'     => 'required|boolean',
            'ordering'     => 'nullable|integer|min:0',
            'category_id'  => 'required|exists:categories,id',
            'published_at' => 'nullable|date',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', true),
            'favorite'  => request('favorite', false),
        ]);
    }
}
