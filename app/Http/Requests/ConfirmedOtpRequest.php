<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ConfirmedOtpRequest",
 *     title="ConfirmedOtpRequest",
 *     type="object",
 *     required={"otp"},
 *     @OA\Property(property="otp", type="string", description="otp code", default="123456"),
 *     @OA\Property(property="mobile", type="string", description="Email", default="09100000000"),
 * )
 */
class ConfirmedOtpRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'otp' => ['required', 'string'],
            'mobile' => ['required', 'string'],
        ];
    }
}
