<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class MakePermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:permission
    {model : Namespace action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** Execute the console command. */
    public function handle(): int
    {
        $model = $this->argument('model');
        $model = Str::studly($model);

        $content = file_get_contents(__DIR__ . '/stubs/permission.stub');
        $content = str_replace(['{{model}}', '{{cmodel}}', '{{umodel}}'], [$model, Str::camel($model), Str::upper($model)], $content);

        $path = base_path('app/Services/Permissions/Models');

        if ( ! is_dir($path) && ! mkdir($path, 0775) && ! is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        if ( ! file_exists($path . '/' . $model . 'Permissions.php')) {
            File::put($path . '/' . $model . 'Permissions.php', $content);
        }

        return Command::SUCCESS;
    }
}
