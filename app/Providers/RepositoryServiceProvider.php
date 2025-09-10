<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /** Register services. */
    public function register(): void
    {
        $path        = app()->path() . '/Repositories';
        $directories = array_diff(scandir($path, SCANDIR_SORT_NONE), ['.', '..', 'BaseRepository.php', 'BaseRepositoryInterface.php']);

        foreach ($directories as $directory) {
            $interface      = 'App\Repositories\\' . $directory . '\\' . $directory . 'RepositoryInterface';
            $implementation = 'App\Repositories\\' . $directory . '\\' . $directory . 'Repository';
            $this->app->singleton($interface, $implementation);
        }
    }

    /** Bootstrap services. */
    public function boot(): void {}
}
