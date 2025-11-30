<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateFlashCardRequest",
 *     title="UpdateFlashCardRequest",
 *     type="object",
 *     required={"title", "favorite"},
 * *     @OA\Property(property="title", type="string", default="test title"),
 * *     @OA\Property(property="front", type="string", default="test front content"),
 * *     @OA\Property(property="back", type="string", default="test back content"),
 * *     @OA\Property(property="favorite", type="boolean", default=false),
 * )
 */
class UpdateFlashCardRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'favorite' => ['required', 'boolean'],
            'front' => ['nullable', 'string'],
            'back' => ['nullable', 'string'],
        ];
    }
}
