<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     title="RegisterRequest",
 *     type="object",
 *     required={"mobile", "password", "name", "family", "confirmed_password"},
 *
 *     @OA\Property(property="mobile", type="string", description="Email", default="09100000000"),
 *     @OA\Property(property="password", type="string", description="Password", default="password"),
 *     @OA\Property(property="name", type="string", description="Name", default="John"),
 *     @OA\Property(property="family", type="string", description="Family", default="Doe"),
 *     @OA\Property(property="confirmed_password", type="string", description="Confirmed Password", default="password"),
 * )
 */
class RegisterRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'mobile' => 'required|numeric|digits:11',
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'confirmed_password' => 'required|string|same:password',
        ];
    }
}
