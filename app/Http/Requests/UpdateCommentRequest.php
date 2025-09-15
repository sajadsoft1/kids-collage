<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateCommentRequest",
 *     title="Update Comment request",
 *     type="object",
 *     required={"comment", "published"},
 *     @OA\Property(property="published", type="boolean", default=true),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, default=null),
 *     @OA\Property(property="comment", type="string", default="This is a comment"),
 *     @OA\Property(property="admin_note", type="string", default="This is aadmin comment "),
 * )
 */
class UpdateCommentRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        $rules              = (new StoreCommentRequest)->rules();
        $rules['admin_note']=['nullable', 'string'];

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
