<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     title="LoginRequest",
 *     description="Login request",
 *     required={"mobile", "password"},
 *
 *     @OA\Property(property="mobile", type="string", description="Email", default="09100000000"),
 *     @OA\Property(property="password", type="string", description="Password", default="password"),
 * )
 */
class LoginRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'mobile' => 'required|numeric|digits:11',
            'password' => 'required|string|min:8',
        ];
    }
}
