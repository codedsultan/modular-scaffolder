<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleRouteCommand extends Command
{
    protected $signature = 'make:module-route {name} {--type=web}';
    protected $description = 'Create a module route file.';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $type = $this->option('type');
        $filename = $type === 'api' ? 'api.php' : 'web.php';

        $path = app_path("Modules/{$name}/Routes/{$filename}");

        if (File::exists($path)) {
            $this->warn("Route file already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $routeContent = $type === 'api'
            ? <<<PHP
<?php

use Illuminate\\Support\\Facades\\Route;

Route::prefix('api')->group(function () {
    //
});
PHP
            : <<<PHP
<?php

use Illuminate\\Support\\Facades\\Route;

Route::middleware('web')->group(function () {
    //
});
PHP;

        File::put($path, $routeContent);
        $this->info("Route file {$filename} created at {$path}");
    }
}
