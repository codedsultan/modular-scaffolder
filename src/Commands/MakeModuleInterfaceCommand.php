<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleInterfaceCommand extends Command
{
    protected $signature = 'make:module-interface {name} {--type=}';
    protected $description = 'Create a module interface in the correct folder (Repository or Service).';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $type = strtolower($this->option('type')) ?? 'service'; // default to service

        // Determine base module name by removing known suffixes
        if (Str::endsWith($name, 'RepositoryInterface')) {
            $module = Str::replaceLast('RepositoryInterface', '', $name);
        } elseif (Str::endsWith($name, 'ServiceInterface')) {
            $module = Str::replaceLast('ServiceInterface', '', $name);
        } else {
            $this->error('Invalid interface name: Must end with RepositoryInterface or ServiceInterface.');
            return;
        }

        $folder = $type === 'repository' ? 'Repository' : 'Service';
        $path = app_path("Modules/{$module}/Interfaces/{$folder}/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Interface already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\Interfaces\\{$folder}";

        $content = <<<PHP
<?php

namespace {$namespace};

interface {$name}
{
    //
}
PHP;

        File::put($path, $content);
        $this->info("Interface {$name} created successfully inside {$folder} at {$path}");
    }
}
