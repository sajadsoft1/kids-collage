<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreNotebookRequest",
 *     title="StoreNotebookRequest",
 *     type="object",
 *     required={"title"},
 *
 *     @OA\Property(property="title", type="string", default="test title"),
 *     @OA\Property(property="description", type="string", default="test description"),
 * )
 */
class StoreNotebookRequest extends FormRequest
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
