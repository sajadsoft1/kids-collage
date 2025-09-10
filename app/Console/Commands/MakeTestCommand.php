<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class MakeTestCommand extends Command
{
    use StrReplaceTrait;

    protected $signature = 'app:test
    {model : Namespace test}
    {--type= : types - (Store-Update-Delete-Data-Toggle-Show-Index) - sample = isSu}
                ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** Execute the console command. */
    public function handle(): void
    {
        $model  = $this->argument('model');
        $model  = Str::studly($model);

        if ($this->option('type') === 'Store') {
            $this->createTest($model, 'Store');
        }

        if ($this->option('type') === 'Update') {
            $this->createTest($model, 'Update');
        }

        if ($this->option('type') === 'Delete') {
            $this->createTest($model, 'Delete');
        }

        if ($this->option('type') === 'Toggle') {
            $this->createTest($model, 'Toggle');
        }

        if ($this->option('type') === 'Data') {
            $this->createTest($model, 'Data');
        }

        if ($this->option('type') === 'Show') {
            $this->createTest($model, 'Show');
        }

        if ($this->option('type') === 'Index') {
            $this->createTest($model, 'Index');
        }
    }

    private function createTest($model, $type): void
    {
        $content_repository = file_get_contents(__DIR__ . '/stubs/tests/feature/' . Str::camel($type) . '.php.stub');
        $content_repository = $this->string_model_replace($model, $content_repository);

        $path = base_path('tests/Feature/Http/Controllers/Api/' . $model);
        if ( ! is_dir($path) && ! mkdir($path, 0775) && ! is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
        $var = $path . '/' . Str::studly($type) . $model . 'Test.php';
        if ( ! file_exists($var)) {
            File::put($var, $content_repository);
        }
    }
}
