<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleCollectionCommand extends Command
{
    protected $signature = 'make:module-collection {module} {name}';
    protected $description = 'Create a resource collection class inside a module.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $name = Str::studly($this->argument('name'));

        $path = app_path("Modules/{$module}/Resources/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Collection already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\Resources";

        $content = <<<PHP
<?php

namespace {$namespace};

use Illuminate\Http\Resources\Json\ResourceCollection;

class {$name} extends ResourceCollection
{
    public function toArray(\$request): array
    {
        return parent::toArray(\$request);
    }
}
PHP;

        File::put($path, $content);
        $this->info("📦 Resource Collection {$name} created successfully at {$path}");
    }
}
