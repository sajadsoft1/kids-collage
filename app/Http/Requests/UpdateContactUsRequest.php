<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\YesNoEnum;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateContactUsRequest",
 *     title="Update ContactUs request",
 *     type="object",
 *     required={"name", "email", "mobile", "comment"},
 *
 *     @OA\Property(property="name", type="string", default="Jane Doe", description="Contact person name"),
 *     @OA\Property(property="email", type="string", format="email", default="jane@example.com", description="Contact email address"),
 *     @OA\Property(property="mobile", type="string", default="09123456789", description="Contact mobile number"),
 *     @OA\Property(property="comment", type="string", default="Updated message content", description="Contact message/comment"),
 *     @OA\Property(property="admin_note", type="string", default="Updated message content", description="Contact admin message/comment"),
 *     @OA\Property(property="follow_up", ref="#/components/schemas/YesNoEnum"),
 * )
 */
class UpdateContactUsRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        $rules = (new StoreContactUsRequest())->rules();
        $rules['admin_note'] = ['nullable', 'string'];
        $rules['follow_up'] = ['required', 'boolean'];
        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->convertOnToBoolean([
            'follow_up' => request('follow_up',false),
        ]);
    }
}

