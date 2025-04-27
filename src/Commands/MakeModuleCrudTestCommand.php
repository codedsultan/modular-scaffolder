<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleCrudTestCommand extends Command
{
    protected $signature = 'make:module-crud-test {module}';
    protected $description = 'Create a test class inside a module with basic CRUD API tests.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $path = app_path("Modules/{$module}/Tests/{$module}Test.php");

        if (File::exists($path)) {
            $this->warn("Test already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\Tests";

        $routeName = Str::kebab(Str::pluralStudly($module));
        $model = $module;

        $content = <<<PHP
<?php

namespace {$namespace};

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\\{$module}\Models\\{$model};

class {$module}Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_{$routeName}()
    {
        \$response = \$this->getJson('/api/{$routeName}');

        \$response->assertStatus(200);
    }

    public function test_can_create_{$routeName}()
    {
        \$data = [
            // TODO: provide required fields
        ];

        \$response = \$this->postJson('/api/{$routeName}', \$data);

        \$response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                // other fields
            ]
        ]);
    }

    public function test_can_show_{$routeName}()
    {
        \$model = {$model}::factory()->create();

        \$response = \$this->getJson('/api/{$routeName}/' . \$model->id);

        \$response->assertStatus(200);
    }

    public function test_can_update_{$routeName}()
    {
        \$model = {$model}::factory()->create();

        \$data = [
            // TODO: provide updated fields
        ];

        \$response = \$this->putJson('/api/{$routeName}/' . \$model->id, \$data);

        \$response->assertStatus(200);
    }

    public function test_can_delete_{$routeName}()
    {
        \$model = {$model}::factory()->create();

        \$response = \$this->deleteJson('/api/{$routeName}/' . \$model->id);

        \$response->assertStatus(204);
    }
}
PHP;

        File::put($path, $content);
        $this->info("✅ CRUD Test class {$module}Test created successfully at {$path}");
    }
}
