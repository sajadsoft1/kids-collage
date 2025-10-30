<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class BotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bot
                {model : Model name to generate components for}
                {--except= : Except actions - (i=index,s=store,S=seeder,u=update,d=delete,f=factory,r=resource,R=request,c=controller,p=policy,y=Repository) - sample = isSu}
                {--force : Overwrite existing files}
                {--simple : Use CRUD table with modal instead of UpdateOrCreate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all base components for an entity (Model, Actions, Policy, Livewire, Routes, etc.)';

    /** Execute the console command. */
    public function handle(): int
    {
        $model   = Str::studly($this->argument('model'));
        $simple  = (bool) $this->option('simple');

        Artisan::call('app:model ' . $model);

        Artisan::call('app:action ' . $model . ' --type=Store');
        Artisan::call('app:action ' . $model . ' --type=Update');
        Artisan::call('app:action ' . $model . ' --type=Delete');

        Artisan::call('app:permission ' . $model);

        Artisan::call('app:policy ' . $model);

        Artisan::call('make:factory ' . $model . 'Factory');

        Artisan::call('app:lang ' . $model);

        Artisan::call('app:route ' . $model . ($simple ? ' --simple' : ''));

        Artisan::call('app:datatable ' . $model);

        // When using simple CRUD with modal inside the datatable and both -d and -t are provided,
        // replace the generated Table with a modal-enabled template and skip UpdateOrCreate views.
        if ($simple) {
            $this->useModalDatatableTemplate($model);
        } else {
            Artisan::call('app:make-livewire-views ' . $model);
        }

        return Command::SUCCESS;
    }

    private function useModalDatatableTemplate(string $model): void
    {
        $stubPath = __DIR__ . '/stubs/datatable-with-modal.php.stub';
        if ( ! file_exists($stubPath)) {
            return;
        }

        $content = file_get_contents($stubPath);
        $content = str_replace(
            [
                '{{model}}',
                '{{cmodel}}',
                '{{kmodel}}',
                '{{smodel}}',
            ],
            [
                $model,
                Str::camel($model),
                Str::kebab($model),
                Str::snake($model),
            ],
            $content
        );

        $destDir  = base_path('app/Livewire/Admin/Pages/' . $model);
        if ( ! is_dir($destDir)) {
            mkdir($destDir, 0775, true);
        }

        file_put_contents($destDir . '/' . $model . 'Table.php', $content);
    }
}
