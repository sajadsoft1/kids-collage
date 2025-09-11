<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="StoreRoleRequest",
 *      title="Store Role request",
 *      type="object",
 *      required={"name"},
 *
 *     @OA\Property(property="name", type="string", default="admin", description="Role name (unique identifier)"),
 *     @OA\Property(property="description", type="string", default="Administrator role", description="Role description"),
 *     @OA\Property(property="permissions", type="array", @OA\Items(type="integer"), example={1, 2, 3}, description="Permission IDs to assign to role"),
 * )
 */
class StoreRoleRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description'     => ['nullable', 'string'],
            'permissions'     => 'nullable|array',
            'permissions.*'   => 'exists:permissions,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertStringToArray([
            'permissions' => request('permissions', []),
        ]);
    }
}
