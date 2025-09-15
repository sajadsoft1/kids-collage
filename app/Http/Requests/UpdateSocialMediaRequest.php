<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateSocialMediaRequest",
 *     title="Update SocialMedia request",
 *     type="object",
 *     required={"title", "link", "position", "published", "ordering"},
 *
 *      @OA\Property(property="title", type="string", default="Facebook", description="Social media platform title"),
 *      @OA\Property(property="link", type="string", default="https://facebook.com/username", description="Social media profile URL"),
 *      @OA\Property(property="ordering", type="integer", default=0, description="Display order"),
 *      @OA\Property(property="position", ref="#/components/schemas/SocialMediaPositionEnum"),
 *      @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *      @OA\Property(property="image", type="string", format="binary", description="Social media icon/image"),
 * )
 */
class UpdateSocialMediaRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return (new StoreSocialMediaRequest())->rules();
    }

    protected function prepareForValidation()
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
