<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ForgetPasswordRequest",
 *     title="ForgetPasswordRequest",
 *     type="object",
 *     required={"mobile"},
 *
 *     @OA\Property(property="mobile", type="string", description="Email", default="09100000000"),
 * )
 */
class ForgetPasswordRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'mobile' => ['required', 'string', 'exists:users,mobile'],
        ];
    }
}
