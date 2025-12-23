<?php

declare(strict_types=1);

namespace App\Services\Menu;

use App\Models\User;
use App\Services\Permissions\PermissionsService;
use Illuminate\Support\Facades\Auth;

class MenuPermissionChecker
{
    /**
     * Check if user has permission based on array of permissions
     *
     * @param array<int|string, mixed> $permissions
     */
    public function checkPermission(array $permissions): bool
    {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        foreach ($permissions as $key => $value) {
            // Allow closures/callables anywhere in the array
            if ($this->evaluateCallable($value, $user)) {
                return true;
            }

            // Keyed by model class => actions
            if (is_string($key) && class_exists($key)) {
                if ($this->userHasAnyAction($user, $key, $value)) {
                    return true;
                }

                continue;
            }

            // Numeric keys with associative array definition
            if (is_array($value)) {
                $model = $value['model'] ?? null;
                $actions = $value['actions'] ?? ($value['action'] ?? null);

                if (is_string($model) && class_exists($model) && $actions !== null) {
                    if ($this->userHasAnyAction($user, $model, $actions)) {
                        return true;
                    }
                }

                // Allow nested closures inside arrays
                foreach ($value as $inner) {
                    if ($this->evaluateCallable($inner, $user)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /** Check if user has access to a specific module */
    public function hasAccessToModule(string $moduleName): bool
    {
        return config("custom-modules.{$moduleName}", false);
    }

    /** Evaluate callable permission check */
    private function evaluateCallable(mixed $value, User $user): bool
    {
        return is_object($value) && is_callable($value)
            ? (bool) $value($user)
            : false;
    }

    /** Check if user has any of the specified actions on the model */
    private function userHasAnyAction(User $user, string $model, mixed $actions): bool
    {
        $actionList = is_array($actions) ? $actions : [$actions];

        foreach ($actionList as $action) {
            if ( ! is_string($action)) {
                continue;
            }

            if ($user->hasAnyPermission(
                PermissionsService::generatePermissionsByModel($model, $action)
            )) {
                return true;
            }
        }

        return false;
    }
}
