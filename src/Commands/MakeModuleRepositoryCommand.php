<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleRepositoryCommand extends Command
{
    protected $signature = 'make:module-repository {name}';
    protected $description = 'Create a module repository.';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $path = app_path("Modules/{$name}/Repositories/Eloquent{$name}Repository.php");

        if (File::exists($path)) {
            $this->warn("Repository already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = <<<PHP
<?php

namespace App\Modules\\{$name}\Repositories;

use App\Modules\\{$name}\Interfaces\\{$name}RepositoryInterface;

class Eloquent{$name}Repository implements {$name}RepositoryInterface
{
    //
}
PHP;

        File::put($path, $content);
        $this->info("Repository created at {$path}");
    }
}
