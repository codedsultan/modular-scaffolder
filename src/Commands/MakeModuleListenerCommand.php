<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleListenerCommand extends Command
{
    protected $signature = 'make:module-listener {module} {name}';
    protected $description = 'Create a listener class inside a module.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $name = Str::studly($this->argument('name'));

        $path = app_path("Modules/{$module}/Listeners/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Listener already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\Listeners";

        $content = <<<PHP
<?php

namespace {$namespace};

class {$name}
{
    public function handle(\$event)
    {
        //
    }
}
PHP;

        File::put($path, $content);
        $this->info("Listener {$name} created successfully at {$path}");
    }
}
