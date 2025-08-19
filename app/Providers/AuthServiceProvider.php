<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Tag;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as BaseAuthProvider;

class AuthServiceProvider extends BaseAuthProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Tag::class => TagPolicy::class,
    ];

    /** Bootstrap services. */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
