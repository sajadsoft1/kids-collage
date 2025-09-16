<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\SliderPositionEnum;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreSliderRequest",
 *     title="Store Slider request",
 *     type="object",
 *     required={"title", "published"," position", "has_timer"," image"},
 *
 *     @OA\Property(property="title", type="string", default="Slider Title", description="Slider title"),
 *     @OA\Property(property="description", type="string", default="Slider description", description="Slider description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="Slider order"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 *     @OA\Property(property="expired_at", type="string", format="date-time", description="Expiration date"),
 *     @OA\Property(property="link", type="string", default="https://example.com", description="Slider link URL"),
 *     @OA\Property(property="position", type="string", enum={"top", "middle", "bottom"}, default="top", description="Slider position"),
 *     @OA\Property(property="has_timer", type="boolean", default=false, description="Whether the slider has a timer"),
 *     @OA\Property(property="timer_start", type="string", format="date-time", description="Timer start date"),
 *     @OA\Property(property="image", type="string", format="binary", description="Slider image"),
 * )
 */
class StoreSliderRequest extends FormRequest
{
    use FillAttributes;



    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'published'       => 'required|boolean',
            'ordering'        => 'required|integer|min:0',
            'published_at'    => 'nullable|date',
            'expired_at'      => 'nullable|date|after:published_at',
            'link'            => 'nullable|url',
            'position'        => 'required|in:' . implode(',', SliderPositionEnum::values()),
            'has_timer'       => 'required|boolean',
            'timer_start'     => 'nullable|date',
            'image'           => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
            'has_timer' => request('has_timer', false),
        ]);

        $this->convertStringToArrayOfObject([
            'roles' => request('roles', []),
        ]);
    }
}
