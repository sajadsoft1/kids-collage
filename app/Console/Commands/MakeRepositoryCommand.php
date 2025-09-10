<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:repository
    {model : Namespace action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make repository';

    /** Execute the console command. */
    public function handle()
    {
        $model = $this->argument('model');
        $model = Str::studly($model);

        $content_repository = file_get_contents(__DIR__ . '/stubs/repository.php.stub');
        $content_repository = str_replace('{{model}}', $model, $content_repository);

        $content_repository_interface = file_get_contents(__DIR__ . '/stubs/repositoryInterface.php.stub');
        $content_repository_interface = str_replace('{{model}}', $model, $content_repository_interface);

        $path = base_path('app/Repositories/' . $model);
        if ( ! is_dir($path) && ! mkdir($path, 0775) && ! is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        if ( ! file_exists($path . '/' . $model . 'Repository.php')) {
            File::put($path . '/' . $model . 'Repository.php', $content_repository);
        }
        if ( ! file_exists($path . '/' . $model . 'RepositoryInterface.php')) {
            File::put($path . '/' . $model . 'RepositoryInterface.php', $content_repository_interface);
        }
    }
}
