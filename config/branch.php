<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Branch ID
    |--------------------------------------------------------------------------
    |
    | The default branch ID to use when no branch is specified in the request.
    | This can be overridden via the DEFAULT_BRANCH_ID environment variable.
    |
    */

    'default_branch_id' => env('DEFAULT_BRANCH_ID', 1),

    /*
    |--------------------------------------------------------------------------
    | Session Key
    |--------------------------------------------------------------------------
    |
    | The session key used to store the current branch ID for web requests.
    |
    */

    'session_key' => 'current_branch_id',

    /*
    |--------------------------------------------------------------------------
    | Header Name
    |--------------------------------------------------------------------------
    |
    | The HTTP header name used to pass the branch ID for API requests.
    |
    */

    'header_name' => 'X-Branch-Id',

    /*
    |--------------------------------------------------------------------------
    | Models Shared Across Branches
    |--------------------------------------------------------------------------
    |
    | List of model classes that are shared across all branches.
    | These models will not have the branch scope applied, but branch_id
    | will still be set on creation for tracking purposes.
    |
    */

    'models_shared_across_branches' => [
        'App\Models\User',
        'App\Models\Role',
        'App\Models\Permission',
    ],

    /*
    |--------------------------------------------------------------------------
    | Models Excluded From Scoping
    |--------------------------------------------------------------------------
    |
    | List of model classes that should never have branch scoping applied.
    | These models will not have branch_id set automatically.
    |
    */

    'models_excluded_from_scoping' => [
        'App\Models\Branch',
    ],
];
