<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateClassroomRequest",
 *     title="Update Classroom request",
 *     type="object",
 *     required={"title", "branch_id", "published"},
 *
 *     @OA\Property(property="title", type="string", default="test title"),
 *     @OA\Property(property="description", type="string", default="test description"),
 *     @OA\Property(property="branch_id", type="integer", default=1),
 *     @OA\Property(property="capacity", type="integer", default=30),
 *     @OA\Property(property="published", type="boolean", default=true),
 * )
 */
class UpdateClassroomRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return (new StoreClassroomRequest)->rules();
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
