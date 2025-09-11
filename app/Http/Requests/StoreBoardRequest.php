<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="StoreBoardRequest",
 *      title="Store Board request",
 *      type="object",
 *      required={"title"},
 *
 *     @OA\Property(property="title", type="string", default="test title", description="Board title"),
 *     @OA\Property(property="description", type="string", default="test description", description="Board description"),
 * )
 */
class StoreBoardRequest extends FormRequest
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
        // Board doesn't use published field based on action
    }
}
