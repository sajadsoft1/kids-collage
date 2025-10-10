<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreContactUsRequest",
 *     title="StoreContactUsRequest",
 *     type="object",
 *     required={"name", "comment","subject"},
 *
 *     @OA\Property(property="name", type="string", default="ahmad dehestani"),
 *     @OA\Property(property="subject", type="string", default="test subject"),
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
            'name'    => ['required', 'string', 'max:255', 'min:3'],
            'subject'    => ['required', 'string', 'max:255', 'min:3'],
            'email'   => ['email', 'max:255', Rule::when(isset($this->mobile), ['nullable']), Rule::when( ! isset($this->mobile), ['required'])],
            'mobile'  => ['numeric', Rule::when(isset($this->email), ['nullable']), Rule::when( ! isset($this->email), ['required'])],
            'comment' => ['required', 'string', 'max:10000'],
        ];
    }
}
