<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="StoreCardRequest",
 *      title="Store Card request",
 *      type="object",
 *      required={"title"},
 *
 *     @OA\Property(property="title", type="string", default="test title", description="Card title"),
 *     @OA\Property(property="description", type="string", default="test description", description="Card description"),
 * )
 */
class StoreCardRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Card doesn't use published field based on action
    }
}
