<?php

declare(strict_types=1);

namespace App\Console\Commands;

use http\Exception\RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeLivewireViewsCommand extends Command
{
    use StrReplaceTrait;

    protected $signature = 'app:make-livewire-views
    {model : Namespace action}';

    protected $description = 'Command description';

    public function handle()
    {
        $model   = $this->argument('model');
        $model   = Str::studly($model);
        $content = file_get_contents(__DIR__ . '/stubs/update-or-create.php.stub');
        $content = $this->string_model_replace($model, $content);

        $path = base_path('app/Livewire/Admin/Pages/' . $model);

        if ( ! is_dir($path) && ! mkdir($path, 0775) && ! is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        if ( ! file_exists($path . '/' . $model . 'UpdateOrCreate.php')) {
            File::put($path . '/' . $model . 'UpdateOrCreate.php', $content);
        }

        // view
        $content = file_get_contents(__DIR__ . '/stubs/update-or-create-view.php.stub');
        $content = $this->string_model_replace($model, $content);

        $path = base_path('resources/views/livewire/admin/pages/' . Str::camel($model));

        if ( ! is_dir($path) && ! mkdir($path, 0775) && ! is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        if ( ! file_exists($path . '/' . Str::camel($model) . '-update-or-create.blade.php')) {
            File::put($path . '/' . Str::camel($model) . '-update-or-create.blade.php', $content);
        }
    }
}
