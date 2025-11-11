<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class MakeActionCommand extends Command
{
    use StrReplaceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:action
    {model : Model name}
    {--type= : Action type - (Store, Update, Delete, Toggle, Data)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Laravel Actions for a model';

    /** Execute the console command. */
    public function handle(): int
    {
        $model = Str::studly($this->argument('model'));
        $type = $this->option('type');

        if (empty($type)) {
            $this->error('❌ Action type is required. Use --type option.');

            return self::FAILURE;
        }

        try {
            $this->createAction($model, $type);
            $this->info("✅ {$type} action for {$model} created successfully!");

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error('❌ Error creating action: ' . $e->getMessage());

            return self::FAILURE;
        }
    }

    private function createAction(string $model, string $type): void
    {
        $stubPath = __DIR__ . '/stubs/actions/' . Str::camel($type) . '.php.stub';

        if ( ! File::exists($stubPath)) {
            throw new RuntimeException("Stub file not found: {$stubPath}");
        }

        $content = File::get($stubPath);
        $content = $this->string_model_replace($model, $content);

        $path = base_path('app/Actions/' . $model);
        if ( ! is_dir($path) && ! mkdir($path, 0775) && ! is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        $actionPath = $path . '/' . Str::studly($type) . $model . 'Action.php';

        if (File::exists($actionPath)) {
            $this->warn("⚠️  Action already exists: {$actionPath}");

            return;
        }

        File::put($actionPath, $content);
    }
}
