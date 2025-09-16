<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateOpinionRequest",
 *     title="Update Opinion request",
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
class UpdateOpinionRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return (new StoreOpinionRequest())->rules();
    }

    protected function prepareForValidation()
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
