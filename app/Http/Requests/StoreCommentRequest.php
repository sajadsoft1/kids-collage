<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreCommentRequest",
 *     title="Store Comment request",
 *     type="object",
 *     required={"comment","published"},
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, default=null),
 *     @OA\Property(property="comment", type="string", default="This is a comment"),
 * )
 */
class StoreCommentRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'published'   => 'required|boolean',
            'parent_id'   => 'nullable|exists:comments,id',
            'comment'     => 'required|string|max:65535',

        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
