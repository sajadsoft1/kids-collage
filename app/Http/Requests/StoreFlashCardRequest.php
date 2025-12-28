<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreFlashCardRequest",
 *     title="StoreFlashCardRequest",
 *     type="object",
 *     required={"front", "favorite"},
 *     @OA\Property(property="front", type="string", default="test front content"),
 *     @OA\Property(property="back", type="string", default="test back content"),
 *     @OA\Property(property="favorite", type="boolean", default=false),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string")),
 * )
 */
class StoreFlashCardRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'front' => ['required', 'string'],
            'back' => ['nullable', 'string'],
            'favorite' => ['required', 'boolean'],
            'taxonomy_id' => ['required', 'exists:taxonomies,id'],
        ];
    }
}
