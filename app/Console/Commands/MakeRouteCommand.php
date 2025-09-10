<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class MakeRouteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:route
    {model : Namespace action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make route';

    /** Execute the console command. */
    public function handle(): int
    {
        $model = $this->argument('model');
        $model = Str::studly($model);

        $route_api = file_get_contents(__DIR__ . '/stubs/route-api.php.stub');
        $route_api = str_replace(
            [
                '{{model}}',
                '{{kmodel}}',
                '{{cmodel}}',
            ],
            [
                $model,
                Str::kebab($model),
                Str::camel($model),
            ],
            $route_api
        );
        $path = base_path('routes/api');

        if ( ! is_dir($path) && ! mkdir($path, 0775) && ! is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        if ( ! file_exists($path . '/' . Str::camel($model) . '.php')) {
            File::put($path . '/' . Str::camel($model) . '.php', $route_api);
        }

        return Command::SUCCESS;
    }
}
