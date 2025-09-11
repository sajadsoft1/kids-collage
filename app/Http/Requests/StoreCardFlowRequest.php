<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="StoreCardFlowRequest",
 *      title="Store CardFlow request",
 *      type="object",
 *      required={"title"},
 *
 *     @OA\Property(property="title", type="string", default="test title", description="CardFlow title"),
 *     @OA\Property(property="description", type="string", default="test description", description="CardFlow description"),
 * )
 */
class StoreCardFlowRequest extends FormRequest
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
        // CardFlow doesn't use published field based on action
    }
}
