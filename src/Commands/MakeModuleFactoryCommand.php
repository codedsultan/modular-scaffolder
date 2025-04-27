<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleFactoryCommand extends Command
{
    protected $signature = 'make:module-factory {module}';
    protected $description = 'Create a model factory for a module model.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));

        $factoryName = "{$module}Factory";
        $factoryPath = database_path("factories/{$factoryName}.php");

        if (File::exists($factoryPath)) {
            $this->warn("Factory already exists at {$factoryPath}");
            return;
        }

        $modelNamespace = "App\Modules\\{$module}\Models\\{$module}";

        $content = <<<PHP
<?php

namespace Database\Factories;

use {$modelNamespace};
use Illuminate\Database\Eloquent\Factories\Factory;

class {$factoryName} extends Factory
{
    protected \$model = {$module}::class;

    public function definition(): array
    {
        return [
            // 'name' => \$this->faker->name,
            // 'email' => \$this->faker->unique()->safeEmail,
            // Fill in your fields here
        ];
    }
}
PHP;

        File::put($factoryPath, $content);

        $this->info("✅ Factory {$factoryName} created successfully at {$factoryPath}");
    }
}
