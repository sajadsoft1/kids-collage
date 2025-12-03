<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreNoteRequest",
 *     title="StoreNoteRequest",
 *     type="object",
 *     required={"question_id", "body"},
 *
 *     @OA\Property(property="question_id", type="integer", default=1),
 *     @OA\Property(property="body", type="string", default="test body"),
 * )
 */
class StoreNoteRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'question_id' => ['required', 'exists:questions,id'],
            'body' => ['nullable', 'string'],
        ];
    }
}
