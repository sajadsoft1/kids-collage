<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class MakeLangCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:lang
    {model : Namespace action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make langs';

    /** Execute the console command. */
    public function handle()
    {
        $active_languages=['en', 'fa'];
        $model           = $this->argument('model');
        $model           = Str::studly($model);

        // lang
        foreach ($active_languages as $activeLang) {
            $content_lang = file_get_contents(__DIR__ . '/stubs/lang-' . $activeLang . '.php.stub');
            $path         = base_path('lang/' . $activeLang);

            if ( ! is_dir($path) && ! mkdir($path, 0775) && ! is_dir($path)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
            }

            if ( ! file_exists($path . '/' . Str::camel($model) . '.php')) {
                File::put($path . '/' . Str::camel($model) . '.php', $content_lang);
            }
        }
    }
}
