<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleResourceCommand extends Command
{
    protected $signature = 'make:module-resource {module} {name}';
    protected $description = 'Create a resource class inside a module.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $name = Str::studly($this->argument('name'));

        $path = app_path("Modules/{$module}/Resources/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Resource already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\Resources";

        $content = <<<PHP
<?php

namespace {$namespace};

use Illuminate\Http\Resources\Json\JsonResource;

class {$name} extends JsonResource
{
    public function toArray(\$request): array
    {
        return [
            'id' => \$this->id,
            // Add your resource fields here
        ];
    }
}
PHP;

        File::put($path, $content);
        $this->info("Resource {$name} created successfully at {$path}");
    }
}
