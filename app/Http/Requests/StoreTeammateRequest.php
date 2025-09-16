<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreTeammateRequest",
 *     title="Store Teammate request",
 *     type="object",
 *     required={"title", "position", "published"},
 *
 *     @OA\Property(property="title", type="string", default="John Doe", description="Teammate name"),
 *     @OA\Property(property="description", type="string", default="Team member description", description="Teammate description"),
 *     @OA\Property(property="bio", type="string", default="Team member biography", description="Teammate biography"),
 *     @OA\Property(property="position", type="string", default="Developer", description="Teammate position"),
 *     @OA\Property(property="birthday", type="string", format="date", default="1990-01-01", description="Teammate birthday"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="email", type="string", format="email", default="teammate@example.com", description="Teammate email"),
 *     @OA\Property(property="image", type="string", format="binary", description="Teammate profile image"),
 *     @OA\Property(property="bio_image", type="string", format="binary", description="Teammate bio image"),
 * )
 */
class StoreTeammateRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'bio'         => ['nullable', 'string'],
            'position'    => ['required', 'string', 'max:255'],
            'birthday'    => ['nullable', 'date'],
            'published'   => 'required|boolean',
            'email'       => ['nullable', 'email'],
            'image'       => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio_image'   => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
