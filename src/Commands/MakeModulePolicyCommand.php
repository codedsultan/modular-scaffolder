<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModulePolicyCommand extends Command
{
    protected $signature = 'make:module-policy {module} {name}';
    protected $description = 'Create a policy class inside a module.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $name = Str::studly($this->argument('name'));

        $path = app_path("Modules/{$module}/Policies/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Policy already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\Policies";

        $content = <<<PHP
<?php

namespace {$namespace};

use App\Models\User;

class {$name}
{
    public function viewAny(User \$user)
    {
        //
    }

    public function view(User \$user, \$model)
    {
        //
    }

    public function create(User \$user)
    {
        //
    }

    public function update(User \$user, \$model)
    {
        //
    }

    public function delete(User \$user, \$model)
    {
        //
    }
}
PHP;

        File::put($path, $content);
        $this->info("Policy {$name} created successfully at {$path}");
    }
}
