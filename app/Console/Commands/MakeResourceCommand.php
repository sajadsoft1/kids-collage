<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:resource
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

        $content       = file_get_contents(__DIR__ . '/stubs/resource.stub');
        $contentDetail = file_get_contents(__DIR__ . '/stubs/resource-detail.stub');
        $content       = str_replace(['{{model}}', '{{cmodel}}', '{{umodel}}'], [$model, Str::camel($model), Str::upper($model)], $content);
        $contentDetail = str_replace(['{{model}}', '{{cmodel}}', '{{umodel}}'], [$model, Str::camel($model), Str::upper($model)], $contentDetail);

        $path = base_path('app/Http/Resources');

        if ( ! file_exists($path . '/' . $model . 'Resource.php')) {
            File::put($path . '/' . $model . 'Resource.php', $content);
        }
        if ( ! file_exists($path . '/' . $model . 'DetailResource.php')) {
            File::put($path . '/' . $model . 'DetailResource.php', $contentDetail);
        }

        return Command::SUCCESS;
    }
}
