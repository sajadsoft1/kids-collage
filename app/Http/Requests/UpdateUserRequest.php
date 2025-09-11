<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     title="Update User request",
 *     type="object",
 *     required={"name", "family", "email", "status"},
 *
 *     @OA\Property(property="name", type="string", default="Jane", description="User first name"),
 *     @OA\Property(property="family", type="string", default="Smith", description="User last name"),
 *     @OA\Property(property="email", type="string", format="email", default="jane@example.com", description="User email address"),
 *     @OA\Property(property="password", type="string", format="password", description="User password (minimum 8 characters, optional for updates)"),
 *     @OA\Property(property="status", type="boolean", default=true, description="User status (active/inactive)"),
 *     @OA\Property(property="rules", type="array", @OA\Items(type="string"), example={"moderator", "user"}, description="User roles"),
 *     @OA\Property(property="avatar", type="string", format="binary", description="User avatar image file"),
 * )
 */
class UpdateUserRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        $rules = (new StoreUserRequest)->rules();

        // Make password optional for updates
        $rules['password'] = ['nullable', 'string', 'min:8'];

        // Handle unique email constraint for updates
        $rules['email'] = ['required', 'email', 'unique:users,email,' . $this->route('user')?->id];

        return $rules;
    }
}
