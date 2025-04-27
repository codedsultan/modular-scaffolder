<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFullModuleCommand extends Command
{
    protected $signature = 'make:full-module {name}';
    protected $description = 'Create a full module structure with controllers, models, services, repositories, routes, and service provider.';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $basePath = app_path("Modules/{$name}");

        $this->info("🔧 Creating module structure for {$name}...");

        // Create main folders
        $directories = [
            'Controllers',
            'Models',
            'Repositories',
            'Interfaces/Repository',
            'Interfaces/Service',
            'Services',
            'Requests',
            'Resources',
            'Routes',
            'Events',
            'Listeners',
            'Policies',
            'DTOs',
            'Enums',
            'Tests',
        ];

        foreach ($directories as $dir) {
            File::ensureDirectoryExists("{$basePath}/{$dir}");
        }

        // Create key files
        $this->createModel($name);
        $this->createController($name);
        $this->createService($name);
        $this->createRepository($name);
        $this->createInterfaces($name);
        $this->createRoutes($name);
        $this->createServiceProvider($name);

        $this->info("✅ Full module structure for {$name} created successfully.");
    }

    protected function createModel($name)
    {
        $path = app_path("Modules/{$name}/Models/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Model already exists at {$path}");
            return;
        }

        $namespace = "App\Modules\\{$name}\Models";

        $content = <<<PHP
<?php

namespace {$namespace};

use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    protected \$guarded = [];
}
PHP;

        File::put($path, $content);
        $this->info("📄 Model created at {$path}");
    }

    protected function createController($name)
    {
        $path = app_path("Modules/{$name}/Controllers/{$name}Controller.php");

        if (File::exists($path)) {
            $this->warn("Controller already exists at {$path}");
            return;
        }

        $namespace = "App\Modules\\{$name}\Controllers";

        $content = <<<PHP
<?php

namespace {$namespace};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class {$name}Controller extends Controller
{
    //
}
PHP;

        File::put($path, $content);
        $this->info("📄 Controller created at {$path}");
    }

    protected function createService($name)
    {
        $path = app_path("Modules/{$name}/Services/{$name}Service.php");

        if (File::exists($path)) {
            $this->warn("Service already exists at {$path}");
            return;
        }

        $namespace = "App\Modules\\{$name}\Services";
        $interfaceNamespace = "App\Modules\\{$name}\Interfaces\Service";

        $content = <<<PHP
<?php

namespace {$namespace};

use {$interfaceNamespace}\\{$name}ServiceInterface;

class {$name}Service implements {$name}ServiceInterface
{
    //
}
PHP;

        File::put($path, $content);
        $this->info("📄 Service created at {$path}");
    }

    protected function createRepository($name)
    {
        $path = app_path("Modules/{$name}/Repositories/Eloquent{$name}Repository.php");

        if (File::exists($path)) {
            $this->warn("Repository already exists at {$path}");
            return;
        }

        $namespace = "App\Modules\\{$name}\Repositories";
        $interfaceNamespace = "App\Modules\\{$name}\Interfaces\Repository";

        $content = <<<PHP
<?php

namespace {$namespace};

use {$interfaceNamespace}\\{$name}RepositoryInterface;

class Eloquent{$name}Repository implements {$name}RepositoryInterface
{
    //
}
PHP;

        File::put($path, $content);
        $this->info("📄 Repository created at {$path}");
    }

    protected function createInterfaces($name)
    {
        // Repository Interface
        $repoInterfacePath = app_path("Modules/{$name}/Interfaces/Repository/{$name}RepositoryInterface.php");

        if (!File::exists($repoInterfacePath)) {
            $repoNamespace = "App\Modules\\{$name}\Interfaces\Repository";

            $repoContent = <<<PHP
<?php

namespace {$repoNamespace};

interface {$name}RepositoryInterface
{
    //
}
PHP;
            File::put($repoInterfacePath, $repoContent);
            $this->info("📄 Repository Interface created at {$repoInterfacePath}");
        }

        // Service Interface
        $serviceInterfacePath = app_path("Modules/{$name}/Interfaces/Service/{$name}ServiceInterface.php");

        if (!File::exists($serviceInterfacePath)) {
            $serviceNamespace = "App\Modules\\{$name}\Interfaces\Service";

            $serviceContent = <<<PHP
<?php

namespace {$serviceNamespace};

interface {$name}ServiceInterface
{
    //
}
PHP;
            File::put($serviceInterfacePath, $serviceContent);
            $this->info("📄 Service Interface created at {$serviceInterfacePath}");
        }
    }

    protected function createRoutes($name)
    {
        // Web Routes
        $webRoutePath = app_path("Modules/{$name}/Routes/web.php");

        if (!File::exists($webRoutePath)) {
            $webContent = <<<PHP
<?php

use Illuminate\Support\Facades\Route;

// Web routes for {$name}
PHP;
            File::put($webRoutePath, $webContent);
            $this->info("📄 Web route file created at {$webRoutePath}");
        }

        // API Routes
        $apiRoutePath = app_path("Modules/{$name}/Routes/api.php");

        if (!File::exists($apiRoutePath)) {
            $apiContent = <<<PHP
<?php

use Illuminate\Support\Facades\Route;

// API routes for {$name}
PHP;
            File::put($apiRoutePath, $apiContent);
            $this->info("📄 API route file created at {$apiRoutePath}");
        }
    }

    protected function createServiceProvider($name)
    {
        $path = app_path("Modules/{$name}/{$name}ServiceProvider.php");

        if (File::exists($path)) {
            $this->warn("Service Provider already exists at {$path}");
            return;
        }

        $namespace = "App\Modules\\{$name}";

        $content = <<<PHP
<?php

namespace {$namespace};

use Illuminate\Support\ServiceProvider;

class {$name}ServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        \$this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        \$this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }
}
PHP;

        File::put($path, $content);
        $this->info("📄 Service Provider created at {$path}");
    }
}
