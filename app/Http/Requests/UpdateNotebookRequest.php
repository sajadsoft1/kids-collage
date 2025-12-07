<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateNotebookRequest",
 *     title="UpdateNotebookRequest",
 *     type="object",
 *     required={"title", "taxonomy_id"},
 * *
 * *     @OA\Property(property="title", type="string", default="test title"),
 * *     @OA\Property(property="body", type="string", default="test body"),
 * *     @OA\Property(property="taxonomy_id", type="integer", default=1),
 * *     @OA\Property(property="tags", type="array", @OA\Items(type="string")),
 * )
 */
class UpdateNotebookRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'taxonomy_id' => ['required', 'exists:taxonomies,id'],
            'body' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
        ];
    }
}
