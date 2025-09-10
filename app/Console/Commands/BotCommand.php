<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

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
    public function handle(): void
    {
        $model = $this->argument('model');
        $model = Str::studly($model);
        $out   = new ConsoleOutput;
        $out->writeln('Starting Bot...');

        Artisan::call('app:model ' . $model);
        $out->writeln('Model created successfully.');

        Artisan::call('app:action ' . $model . ' --type=Store');
        $out->writeln('Action Store Created successfully.');
        //        Artisan::call('app:test ' . $model . ' --type=Store');
        $out->writeln('Test for Store Action Created successfully.');
        Artisan::call('app:action ' . $model . ' --type=Update');
        $out->writeln('Action Update Created successfully.');
        //        Artisan::call('app:test ' . $model . ' --type=Update');
        $out->writeln('Test for Update Action Created successfully.');
        Artisan::call('app:action ' . $model . ' --type=Delete');
        $out->writeln('Action Delete Created successfully.');
        //        Artisan::call('app:test ' . $model . ' --type=Delete');
        $out->writeln('Test for Delete Action Created successfully.');

        Artisan::call('app:permission ' . $model);
        $out->writeln('Permission Created successfully.');
        //        Artisan::call('app:test ' . $model . ' --type=Show');
        $out->writeln('Test for Show Action Created successfully.');
        //        Artisan::call('app:test ' . $model . ' --type=Index');
        $out->writeln('Test for Index Action Created successfully.');

        if ($this->option('toggle')) {
            Artisan::call('app:action ' . $model . ' --type=Toggle');
            $out->writeln('Action Toggle Created successfully.');
            //            Artisan::call('app:test ' . $model . ' --type=Toggle');
            $out->writeln('Test for Toggle Action Created successfully.');
        }

        if ($this->option('data')) {
            Artisan::call('app:action ' . $model . ' --type=Data');
            $out->writeln('Action Data Created successfully.');
            //            Artisan::call('app:test ' . $model . ' --type=Data');
            $out->writeln('Test for Data Action Created successfully.');
        }

        Artisan::call('app:policy ' . $model);
        $out->writeln('Policy Created successfully.');

        Artisan::call('app:resource ' . $model);
        $out->writeln('Resource Created successfully.');

        Artisan::call('app:request-store ' . $model);
        $out->writeln('Request Store Created successfully.');
        Artisan::call('app:request-update ' . $model);
        $out->writeln('Request Update Created successfully.');

        Artisan::call('make:factory ' . $model . 'Factory');
        $out->writeln('Factory Created successfully.');

        Artisan::call('app:lang ' . $model);
        $out->writeln('Lang Created successfully.');

        Artisan::call('app:repository ' . $model);
        $out->writeln('Repository Created successfully.');

        Artisan::call('app:route ' . $model);
        $out->writeln('Route Created successfully.');

        Artisan::call('app:controller ' . $model);
        $out->writeln('Controller Created successfully.');
        Artisan::call('app:a_search ' . $model);
        $out->writeln('Advance Search Created successfully.');
    }
}
