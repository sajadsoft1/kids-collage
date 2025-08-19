<?php

declare(strict_types=1);

namespace App\Console\Commands;

use http\Exception\RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDatatableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:datatable
    {model : Namespace action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** Execute the console command. */
    public function handle()
    {
        $model = $this->argument('model');
        $model = Str::studly($model);

        $content_controller = file_get_contents(__DIR__ . '/stubs/datatable.php.stub');
        $content_controller = str_replace(
            [
                '{{model}}',
                '{{kmodel}}',
                '{{cmodel}}',
                '{{smodel}}',
            ],
            [
                $model,
                Str::kebab($model),
                Str::camel($model),
                Str::snake($model),
            ],
            $content_controller
        );
        $path = base_path('app/Livewire/Admin/Pages/' . $model);

        if ( ! is_dir($path) && ! mkdir($path, 0775) && ! is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        if ( ! file_exists($path . '/' . $model . 'Table.php')) {
            File::put($path . '/' . $model . 'Table.php', $content_controller);
        }
    }
}
