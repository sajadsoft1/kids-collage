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
                {--t|toggle : Add toggle action}
                {--d|data : Add data action}
                {--force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all base components for an entity (Model, Actions, Policy, Livewire, Routes, etc.)';

    /** Execute the console command. */
    public function handle(): int
    {
        $model = $this->argument('model');
        $model = Str::studly($model);

        Artisan::call('app:model ' . $model);

        Artisan::call('app:action ' . $model . ' --type=Store');
        Artisan::call('app:action ' . $model . ' --type=Update');
        Artisan::call('app:action ' . $model . ' --type=Delete');

        Artisan::call('app:permission ' . $model);

        Artisan::call('app:policy ' . $model);

        Artisan::call('make:factory ' . $model . 'Factory');

        Artisan::call('app:lang ' . $model);

        Artisan::call('app:route ' . $model);

        Artisan::call('app:datatable ' . $model);
        Artisan::call('app:make-livewire-views ' . $model);

        return Command::SUCCESS;
    }
}
