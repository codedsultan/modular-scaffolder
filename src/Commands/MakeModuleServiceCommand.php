<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleServiceCommand extends Command
{
    protected $signature = 'make:module-service {name}';
    protected $description = 'Create a module service.';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $path = app_path("Modules/{$name}/Services/{$name}Service.php");

        if (File::exists($path)) {
            $this->warn("Service already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = <<<PHP
<?php

namespace App\Modules\\{$name}\Services;

use App\Modules\\{$name}\Interfaces\\{$name}ServiceInterface;

class {$name}Service implements {$name}ServiceInterface
{
    //
}
PHP;

        File::put($path, $content);
        $this->info("Service created at {$path}");
    }
}
