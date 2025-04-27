<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleAddCrudCommand extends Command
{
    protected $signature = 'module:add-crud {module}';
    protected $description = 'Add basic CRUD operations (controller, requests, resource, routes) to a module.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));

        $this->info("⚙️ Adding CRUD for {$module}...");

        $this->createRequests($module);
        $this->createResource($module);

        // $this->call('make:module-resource', [
        //     'module' => $module,
        //     'name' => "{$module}Resource",
        // ]);
        $this->call('make:module-collection', [
            'module' => $module,
            'name' => "{$module}Collection",
        ]);

        $this->updateController($module);
        $this->updateApiRoutes($module);

        $this->info("✅ CRUD for {$module} added successfully!");
    }

    protected function createRequests($module)
    {
        $requests = ['Create', 'Update'];

        foreach ($requests as $type) {
            $className = "{$type}{$module}Request";
            $path = app_path("Modules/{$module}/Requests/{$className}.php");

            if (File::exists($path)) {
                $this->warn("Request {$className} already exists.");
                continue;
            }

            File::ensureDirectoryExists(dirname($path));

            $namespace = "App\Modules\\{$module}\Requests";

            $content = <<<PHP
<?php

namespace {$namespace};

use Illuminate\Foundation\Http\FormRequest;

class {$className} extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Define your validation rules here
        ];
    }
}
PHP;

            File::put($path, $content);
            $this->info("📄 Request {$className} created.");
        }
    }

    protected function createResource($module)
    {
        $className = "{$module}Resource";
        $path = app_path("Modules/{$module}/Resources/{$className}.php");

        if (File::exists($path)) {
            $this->warn("Resource {$className} already exists.");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\Resources";

        $content = <<<PHP
<?php

namespace {$namespace};

use Illuminate\Http\Resources\Json\JsonResource;

class {$className} extends JsonResource
{
    public function toArray(\$request): array
    {
        return [
            'id' => \$this->id,
            // Add other fields
        ];
    }
}
PHP;

        File::put($path, $content);
        $this->info("📄 Resource {$className} created.");
    }

    protected function updateController($module)
    {
        $path = app_path("Modules/{$module}/Controllers/{$module}Controller.php");

        if (!File::exists($path)) {
            $this->warn("❗ Controller {$module}Controller.php does not exist.");
            return;
        }

        $namespace = "App\Modules\\{$module}\Controllers";
        $modelNamespace = "App\Modules\\{$module}\Models\\{$module}";
        $resourceNamespace = "App\Modules\\{$module}\Resources\\{$module}Resource";
        $createRequestNamespace = "App\Modules\\{$module}\Requests\\Create{$module}Request";
        $updateRequestNamespace = "App\Modules\\{$module}\Requests\\Update{$module}Request";

        $content = <<<PHP
<?php

namespace {$namespace};

use App\Http\Controllers\Controller;
use {$modelNamespace};
use {$resourceNamespace};
use {$createRequestNamespace};
use {$updateRequestNamespace};
use Illuminate\Http\Request;

class {$module}Controller extends Controller
{
    public function index()
    {
        \$products = {$module}::paginate(10);
        return {$module}Resource::collection(\$products);
    }

    public function store(Create{$module}Request \$request)
    {
        \$product = {$module}::create(\$request->validated());
        return new {$module}Resource(\$product);
    }

    public function show({$module} \$product)
    {
        return new {$module}Resource(\$product);
    }

    public function update(Update{$module}Request \$request, {$module} \$product)
    {
        \$product->update(\$request->validated());
        return new {$module}Resource(\$product);
    }

    public function destroy({$module} \$product)
    {
        \$product->delete();
        return response()->noContent();
    }
}
PHP;

        File::put($path, $content);
        $this->info("🔧 Controller {$module}Controller updated with CRUD methods.");
    }

    protected function updateApiRoutes($module)
    {
        $path = app_path("Modules/{$module}/Routes/api.php");

        if (!File::exists($path)) {
            $this->warn("❗ API route file does not exist.");
            return;
        }

        $routeContent = <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use App\Modules\\{$module}\Controllers\\{$module}Controller;

Route::apiResource('{$this->toKebabCase($module)}s', {$module}Controller::class);
PHP;

        File::put($path, $routeContent);
        $this->info("🔧 API routes updated for {$module}.");
    }

    protected function toKebabCase($string)
    {
        return Str::kebab(Str::pluralStudly($string));
    }
}
