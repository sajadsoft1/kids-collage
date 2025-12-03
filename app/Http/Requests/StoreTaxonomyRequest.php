<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\TaxonomyTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreTaxonomyRequest",
 *     title="StoreTaxonomyRequest",
 *     type="object",
 *     required={"name", "type", "color"},
 *
 *     @OA\Property(property="name", type="string", default="test name"),
 *     @OA\Property(property="type", type="string", default="flshcard"),
 *     @OA\Property(property="color", type="string", default="#ff0000"),
 * )
 */
class StoreTaxonomyRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(TaxonomyTypeEnum::values())],
            'color' => ['required', 'string'],
        ];
    }
}
