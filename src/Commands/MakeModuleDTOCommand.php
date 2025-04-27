<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleDTOCommand extends Command
{
    protected $signature = 'make:module-dto {module} {name}';
    protected $description = 'Create a DTO class inside a module.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $name = Str::studly($this->argument('name'));

        $path = app_path("Modules/{$module}/DTOs/{$name}.php");

        if (File::exists($path)) {
            $this->warn("DTO already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\DTOs";

        $content = <<<PHP
<?php

namespace {$namespace};

class {$name}
{
    // Define public properties or constructor here
}
PHP;

        File::put($path, $content);
        $this->info("DTO {$name} created successfully at {$path}");
    }
}
