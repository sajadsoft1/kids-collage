<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreUserRequest",
 *     title="Store User request",
 *     type="object",
 *     required={"name", "family", "email", "password", "status"},
 *
 *     @OA\Property(property="name", type="string", default="John", description="User first name"),
 *     @OA\Property(property="family", type="string", default="Doe", description="User last name"),
 *     @OA\Property(property="email", type="string", format="email", default="john@example.com", description="User email address"),
 *     @OA\Property(property="password", type="string", format="password", default="password", description="User password (minimum 8 characters)"),
 *     @OA\Property(property="status", type="boolean", default=true, description="User status (active/inactive)"),
 *     @OA\Property(property="rules", type="array", @OA\Items(type="string"), example={"admin", "user"}, description="User roles"),
 *     @OA\Property(property="avatar", type="string", format="binary", description="User avatar image file"),
 * )
 */
class StoreUserRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'family'      => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8'],
            'status'      => 'required|boolean',
            'rules'       => 'nullable|array',
            'rules.*'     => 'exists:roles,name',
            'avatar'      => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
