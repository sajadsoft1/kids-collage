<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="UpdateRoleRequest",
 *      title="Update Role request",
 *      type="object",
 *      required={"name"},
 *
 *     @OA\Property(property="name", type="string", default="moderator", description="Role name (unique identifier)"),
 *     @OA\Property(property="description", type="string", default="Updated moderator role", description="Role description"),
 *     @OA\Property(property="permissions", type="array", @OA\Items(type="integer"), example={1, 2, 4}, description="Permission IDs to assign to role"),
 * )
 */
class UpdateRoleRequest extends StoreRoleRequest {}
