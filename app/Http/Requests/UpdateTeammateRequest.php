<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateTeammateRequest",
 *     title="Update Teammate request",
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
class UpdateTeammateRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return (new StoreTeammateRequest())->rules();
    }

    protected function prepareForValidation()
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
