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
 *     required={"front", "favorite"},
 *     @OA\Property(property="front", type="string", default="test front content"),
 *     @OA\Property(property="back", type="string", default="test back content"),
 *     @OA\Property(property="favorite", type="boolean", default=false),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string")),
 * )
 */
class UpdateFlashCardRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'front' => ['nullable', 'string'],
            'back' => ['nullable', 'string'],
            'favorite' => ['required', 'boolean'],
            'taxonomy_id' => ['required', 'exists:taxonomies,id'],
        ];
    }
}
