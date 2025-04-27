<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleEventCommand extends Command
{
    protected $signature = 'make:module-event {module} {name}';
    protected $description = 'Create an event class inside a module.';

    public function handle()
    {
        $module = Str::studly($this->argument('module'));
        $name = Str::studly($this->argument('name'));

        $path = app_path("Modules/{$module}/Events/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Event already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $namespace = "App\Modules\\{$module}\Events";

        $content = <<<PHP
<?php

namespace {$namespace};

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class {$name}
{
    use Dispatchable, SerializesModels;

    public function __construct()
    {
        //
    }
}
PHP;

        File::put($path, $content);
        $this->info("Event {$name} created successfully at {$path}");
    }
}
