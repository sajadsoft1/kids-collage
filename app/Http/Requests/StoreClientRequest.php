<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreClientRequest",
 *     title="Store Client request",
 *     type="object",
 *     required={"title", "published"},
 *
 *     @OA\Property(property="title", type="string", default="test title", description="Client title"),
 *     @OA\Property(property="description", type="string", default="test description", description="Client description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="link", type="string", default="https://example.com", description="Client website link"),
 *     @OA\Property(property="image", type="string", format="binary", description="Client logo/image file"),
 * )
 */
class StoreClientRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'published'   => 'required|boolean',
            'link'        => 'nullable|url',
            'image'       => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
