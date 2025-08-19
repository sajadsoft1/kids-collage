<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:model
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

        $content_policy = file_get_contents(__DIR__ . '/stubs/model.stub');
        $content_policy = str_replace(['{{model}}', '{{cmodel}}', '{{umodel}}'], [$model, Str::camel($model), Str::upper($model)], $content_policy);

        $path = base_path('app/Models');

        if ( ! file_exists($path . '/' . $model . '.php')) {
            File::put($path . '/' . $model . '.php', $content_policy);
        }

        if ( ! $this->checkMigrationExist('create_' . Str::snake(Str::plural($model)))) {
            Artisan::call('make:migration create_' . Str::snake(Str::plural($model)) . '_table --create=' . Str::snake(Str::plural($model)));
            $this->info('Make ' . $model . ' migration Successfully.');
        }

        $this->info('Make ' . $model . ' model Successfully.');

        return Command::SUCCESS;
    }

    private function checkMigrationExist(string $string): bool
    {
        $files = array_diff(scandir(database_path('migrations'), SCANDIR_SORT_ASCENDING), ['.', '..']);
        foreach ($files as $name) {
            if (str_contains($name, $string)) {
                return true;
            }
        }

        return false;
    }
}
