<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="StoreContactUsRequest",
 *      title="Store ContactUs request",
 *      type="object",
 *      required={"name", "email", "mobile", "comment"},
 *
 *     @OA\Property(property="name", type="string", default="John Doe", description="Contact person name"),
 *     @OA\Property(property="email", type="string", format="email", default="john@example.com", description="Contact email address"),
 *     @OA\Property(property="mobile", type="string", default="09123456789", description="Contact mobile number"),
 *     @OA\Property(property="comment", type="string", default="Hello, I need help with...", description="Contact message/comment"),
 * )
 */
class StoreContactUsRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email'],
            'mobile'          => ['required', 'numeric', 'digits:11'],
            'comment'         => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // ContactUs doesn't use published field based on action
    }
}
