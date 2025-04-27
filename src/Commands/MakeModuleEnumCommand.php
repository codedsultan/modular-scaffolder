<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleEnumCommand extends Command
{
    protected $signature = 'make:module-enum {module} {name}';
    protected $description = 'Create an Enum class inside a module.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $name = Str::studly($this->argument('name'));

        $path = app_path("Modules/{$module}/Enums/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Enum already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\Enums";

        $content = <<<PHP
<?php

namespace {$namespace};

enum {$name}: string
{
    // case EXAMPLE = 'example';
}
PHP;

        File::put($path, $content);
        $this->info("Enum {$name} created successfully at {$path}");
    }
}
