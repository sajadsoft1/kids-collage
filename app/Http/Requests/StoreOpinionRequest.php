<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreOpinionRequest",
 *     title="Store Opinion request",
 *     type="object",
 *     required={"user_name", "comment", "published","ordering" },
 *
 *     @OA\Property(property="user_name", type="string", default="John Doe", description="Opinion author name"),
 *     @OA\Property(property="comment", type="string", default="Great service! Highly recommended.", description="Opinion comment/review"),
 *     @OA\Property(property="company", type="string", default="ABC Company", description="Author's company"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="Opinion order"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 *     @OA\Property(property="image", type="string", format="binary", description="Author profile image"),
 * )
 */
class StoreOpinionRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'user_name'    => ['required', 'string', 'max:255'],
            'comment'      => ['required', 'string'],
            'company'      => ['nullable', 'string', 'max:255'],
            'published'    => 'required|boolean',
            'ordering'     => 'required|integer|min:0',
            'published_at' => 'nullable|date',
            'image'        => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
