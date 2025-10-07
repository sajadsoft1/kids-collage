<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="StoreContactUsRequest",
 *      title="StoreContactUsRequest",
 *      type="object",
 *      required={"name","email","mobile","comment"},
 *
 *     @OA\Property(property="name", type="string", default="ahmad dehestani"),
 *     @OA\Property(property="email", type="string", format="email", default="s.ahmad.dehestani@gmail.com"),
 *     @OA\Property(property="mobile", type="string", default=09123456789),
 *     @OA\Property(property="comment", type="string", default="test comment"),
 * )
 */
class StoreContactUsRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255','min:3'],
            'email'           => ['required', 'email', 'max:255'],
            'mobile'           => ['required', 'numeric' ],
            'comment'           => ['required', 'string', 'max:10000'],

        ];
    }
}
