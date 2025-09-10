<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class MakeAdvancedSearchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:a_search
    {model : Namespace action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make search';

    /** Execute the console command. */
    public function handle(): void
    {
        $model = $this->argument('model');
        $model = Str::studly($model);

        $handler = file_get_contents(__DIR__ . '/stubs/a_search/handler.stub');
        $handler = str_replace('{{model}}', $model, $handler);

        $driver = file_get_contents(__DIR__ . '/stubs/a_search/driver.stub');
        $driver = str_replace('{{model}}', $model, $driver);

        $pathDriver = base_path('app/Services/AdvancedSearchFields/Drivers');
        if ( ! is_dir($pathDriver) && ! mkdir($pathDriver, 0775) && ! is_dir($pathDriver)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $pathDriver));
        }

        $pathHandler = base_path('app/Services/AdvancedSearchFields/Handlers');
        if ( ! is_dir($pathHandler) && ! mkdir($pathHandler, 0775) && ! is_dir($pathHandler)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $pathHandler));
        }

        if ( ! file_exists($pathHandler . '/' . $model . 'Handler.php')) {
            File::put($pathHandler . '/' . $model . 'Handler.php', $handler);
        }

        if ( ! file_exists($pathDriver . '/' . $model . 'Driver.php')) {
            File::put($pathDriver . '/' . $model . 'Driver.php', $driver);
        }
    }
}
