<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="StoreOpinionRequest",
 *      title="Store Opinion request",
 *      type="object",
 *      required={"title"},
 *
 *     @OA\Property(property="title", type="string", default="test title"),
 *     @OA\Property(property="description", type="string", default="test description"),
 * )
 */
class StoreOpinionRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'published'       => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
