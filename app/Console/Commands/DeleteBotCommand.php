<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DeleteBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deletebot
    {model : Namespace action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** Execute the console command. */
    public function handle(): void
    {
        $model = $this->argument('model');
        $model = Str::studly($model);

        $path = base_path('app/Actions/' . $model);
        $this->deleteFolder($path);

        $path = base_path('app/Livewire/Admin/Pages/' . $model);
        $this->deleteFolder($path);

        $path = base_path('resources/views/livewire/admin/pages/' . $model);
        $this->deleteFolder($path);

        $path = base_path('app/Models/' . $model . '.php');
        $this->deleteFile($path);

        $path = base_path('app/Policies/' . $model . 'Policy.php');
        $this->deleteFile($path);

        $path = base_path('database/factories/' . $model . 'Factory.php');
        $this->deleteFile($path);

        $path = base_path('database/seeders/' . $model . 'Seeder.php');
        $this->deleteFile($path);

        $path = base_path('lang/en' . '/' . $model . '.php');
        $this->deleteFile($path);

        $path = base_path('lang/fa' . '/' . $model . '.php');
        $this->deleteFile($path);

        $path = base_path('routes/admin' . '/' . $model . '.php');
        $this->deleteFile($path);

        // permissions
        $path = base_path('app/Services/Permissions/Models/' . $model . 'Permissions.php');
        $this->deleteFile($path);

        $this->removeMigrations(Str::snake(Str::plural($model)));
    }

    private function deleteFile(string $path): void
    {
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    private function deleteFolder(string $dir): bool
    {
        if ( ! file_exists($dir)) {
            return true;
        }

        if ( ! is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir, SCANDIR_SORT_NONE) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            if ( ! $this->deleteFolder($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    private function removeMigrations(string $string): void
    {
        $string .= '_table';
        $files = array_diff(scandir(database_path('migrations'), SCANDIR_SORT_ASCENDING), ['.', '..']);
        foreach ($files as $name) {
            if (str_contains($name, $string)) {
                $path = base_path('database/migrations/' . $name);
                $this->deleteFile($path);
            }
        }
    }
}
