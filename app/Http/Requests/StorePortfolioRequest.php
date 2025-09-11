<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StorePortfolioRequest",
 *     title="Store Portfolio request",
 *     type="object",
 *     required={"title", "published", "execution_date", "category_id"},
 *
 *     @OA\Property(property="title", type="string", default="Project Name", description="Portfolio title"),
 *     @OA\Property(property="description", type="string", default="Project description", description="Portfolio description"),
 *     @OA\Property(property="body", type="string", default="<p>Detailed project information...</p>", description="Portfolio detailed content"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 *     @OA\Property(property="execution_date", type="string", format="date", default="2024-01-01", description="Project execution date"),
 *     @OA\Property(property="category_id", type="integer", default=1, description="Portfolio category ID"),
 *     @OA\Property(property="creator_id", type="integer", description="Creator user ID (optional, defaults to current user)"),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"web", "design"}, description="Portfolio tags"),
 *     @OA\Property(property="image", type="string", format="binary", description="Portfolio featured image"),
 * )
 */
class StorePortfolioRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'body'           => ['nullable', 'string'],
            'published'      => 'required|boolean',
            'published_at'   => 'nullable|date',
            'execution_date' => 'required|date',
            'category_id'    => 'required|exists:categories,id',
            'creator_id'     => 'nullable|exists:users,id',
            'tags'           => 'nullable|array',
            'image'          => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);

        $this->convertStringToArray([
            'tags' => request('tags', []),
        ]);
    }
}
