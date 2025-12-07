<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreCommentRequest",
 *     title="StoreCommentRequest",
 *     type="object",
 *     required={"comment"},
 *
 *     @OA\Property(property="comment", type="string", default="test comment"),
 *     @OA\Property(property="parent_id", type="nullable|integer", default=null),
 * )
 */
class StoreCommentRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'exists:comments,id'],
            'comment' => ['required', 'string'],
        ];
    }
}
