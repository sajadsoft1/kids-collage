<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="UpdateSliderRequest",
 *      title="Update Slider request",
 *      type="object",
 *      required={"title", "published"},
 *
 *     @OA\Property(property="title", type="string", default="Updated Slider Title", description="Slider title"),
 *     @OA\Property(property="description", type="string", default="Updated slider description", description="Slider description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="Slider order"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 *     @OA\Property(property="expired_at", type="string", format="date-time", description="Expiration date"),
 *     @OA\Property(property="link", type="string", default="https://example.com", description="Slider link URL"),
 *     @OA\Property(property="position", type="string", enum={"top", "middle", "bottom"}, default="top", description="Slider position"),
 *     @OA\Property(property="has_timer", type="boolean", default=false, description="Has timer functionality"),
 *     @OA\Property(property="timer_start", type="string", format="date-time", description="Timer start date"),
 *     @OA\Property(property="image", type="string", format="binary", description="Slider image"),
 *     @OA\Property(property="roles", type="array", @OA\Items(
 *         type="object",
 *         @OA\Property(property="type", type="string", example="App\\Models\\Role"),
 *         @OA\Property(property="value", type="array", @OA\Items(type="integer"), example={1, 2})
 *     ), description="Roles/permissions for slider visibility"),
 * )
 */
class UpdateSliderRequest extends StoreSliderRequest {}
