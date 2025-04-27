<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleControllerCommand extends Command
{
    protected $signature = 'make:module-controller {name} {--model=} {--api}';
    protected $description = 'Create a module controller with optional model binding and API resource structure.';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $model = $this->option('model') ? Str::studly($this->option('model')) : null;
        $path = app_path("Modules/{$name}/Controllers/{$name}Controller.php");

        if (File::exists($path)) {
            $this->warn("Controller already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$name}\Controllers";
        $modelNamespace = $model ? "use App\Modules\\{$name}\Models\\{$model};" : '';

        $methods = $this->option('api') ? <<<PHP
    public function index()
    {
        //
    }

    public function store(Request \$request)
    {
        //
    }

    public function show(\$id)
    {
        //
    }

    public function update(Request \$request, \$id)
    {
        //
    }

    public function destroy(\$id)
    {
        //
    }
PHP
: <<<PHP
    public function __construct()
    {
        //
    }
PHP;

        $content = <<<PHP
<?php

namespace {$namespace};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
{$modelNamespace}

class {$name}Controller extends Controller
{
{$methods}
}
PHP;

        File::put($path, $content);
        $this->info("Controller created at {$path}");
    }
}
