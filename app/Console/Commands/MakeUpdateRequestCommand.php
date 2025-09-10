<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeUpdateRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:request-update
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

        $content_policy = file_get_contents(__DIR__ . '/stubs/request-update.stub');
        $content_policy = str_replace(['{{model}}', '{{cmodel}}', '{{umodel}}'], [$model, Str::camel($model), Str::upper($model)], $content_policy);

        $path = base_path('app/Http/Requests');

        if ( ! file_exists($path . '/Update' . $model . 'Request.php')) {
            File::put($path . '/Update' . $model . 'Request.php', $content_policy);
        }

        return Command::SUCCESS;
    }
}
