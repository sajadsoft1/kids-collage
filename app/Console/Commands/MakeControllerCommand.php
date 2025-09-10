<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:controller
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

        $content_controller = file_get_contents(__DIR__ . '/stubs/controller.php.stub');
        $content_controller = str_replace(
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
            $content_controller
        );
        $path = base_path('app/Http/Controllers/Api/');

        if ( ! file_exists($path . '/' . $model . 'Controller.php')) {
            File::put($path . '/' . $model . 'Controller.php', $content_controller);
        }
    }
}
