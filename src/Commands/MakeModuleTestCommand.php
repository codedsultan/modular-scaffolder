<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleTestCommand extends Command
{
    protected $signature = 'make:module-test {module} {name}';
    protected $description = 'Create a test class inside a module.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $name = Str::studly($this->argument('name'));

        $path = app_path("Modules/{$module}/Tests/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Test already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\Tests";

        $content = <<<PHP
<?php

namespace {$namespace};

use Tests\TestCase;

class {$name} extends TestCase
{
    public function test_example()
    {
        \$this->assertTrue(true);
    }
}
PHP;

        File::put($path, $content);
        $this->info("Test {$name} created successfully at {$path}");
    }
}
