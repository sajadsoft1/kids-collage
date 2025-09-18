<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateBranchRequest",
 *     title="Update Branch request",
 *     type="object",
 *     required={"title"},
 *     @OA\Property(property="title", type="string", default="test title"),
 *     @OA\Property(property="description", type="string", default="test description"),
 *     @OA\Property(property="address", type="string", default="test address"),
 *     @OA\Property(property="phone", type="string", default="1234567890"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 * )
 */
class UpdateBranchRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return (new StoreBranchRequest)->rules();
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
