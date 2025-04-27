<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleProviderCommand extends Command
{
    protected $signature = 'make:module-provider {name}';
    protected $description = 'Create a module service provider.';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $path = app_path("Modules/{$name}/{$name}ServiceProvider.php");

        if (File::exists($path)) {
            $this->warn("Service Provider already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = <<<PHP
<?php

namespace App\Modules\\{$name};

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
        $this->info("Service Provider created at {$path}");
    }
}
