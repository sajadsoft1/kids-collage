<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRequestCommand extends Command
{
    use StrReplaceTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:request
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

        $content = file_get_contents(__DIR__ . '/stubs/request.stub');
        $content = $this->string_model_replace($model, $content);

        $path = base_path('app/Http/Requests');

        if ( ! file_exists($path . $model . 'Request.php')) {
            File::put($path . $model . 'Request.php', $content);
        }

        return Command::SUCCESS;
    }
}
