<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeModuleModelCommand extends Command
{
    protected $signature = 'make:module-model {name} {--migration}';
    protected $description = 'Create a module model with optional migration.';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $path = app_path("Modules/{$name}/Models/{$name}.php");

        if (File::exists($path)) {
            $this->warn("Model already exists at {$path}");
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = <<<PHP
<?php

namespace App\Modules\\{$name}\Models;

use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    protected \$guarded = [];
}
PHP;

        File::put($path, $content);
        $this->info("Model created at {$path}");

        if ($this->option('migration')) {
            $tableName = Str::snake(Str::pluralStudly($name));
            $this->call('make:migration', [
                'name' => "create_{$tableName}_table",
                '--create' => $tableName,
            ]);
        }
    }
}
