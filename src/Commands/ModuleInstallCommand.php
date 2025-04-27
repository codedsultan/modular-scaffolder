<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ModuleInstallCommand extends Command
{
    protected $signature = 'module:install {name}';
    protected $description = 'Fully install a new module with all basic files, bindings, and test/factory setup.';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        $this->info("🔧 Installing module: {$name}");

        // Scaffold main structure
        $this->call('make:full-module', ['name' => $name]);

        // Create DTO
        $this->call('make:module-dto', [
            'module' => $name,
            'name' => "Create{$name}DTO",
        ]);

        // Create Enum
        $this->call('make:module-enum', [
            'module' => $name,
            'name' => "{$name}TypeEnum",
        ]);

        // Create Event
        $this->call('make:module-event', [
            'module' => $name,
            'name' => "{$name}CreatedEvent",
        ]);

        // Create Listener
        $this->call('make:module-listener', [
            'module' => $name,
            'name' => "Handle{$name}Created",
        ]);

        // Create Policy
        $this->call('make:module-policy', [
            'module' => $name,
            'name' => "{$name}Policy",
        ]);

        // Create API Test Class
        $this->call('make:module-crud-test', [
            'module' => $name,
        ]);

        // Create Factory (NEW!! 🚀)
        $this->call('make:module-factory', [
            'module' => $name,
        ]);

        // Update bindings inside ServiceProvider
        $this->updateServiceProvider($name);

        $this->info("✅ Module {$name} fully installed successfully!");
    }

    protected function updateServiceProvider($name)
    {
        $providerPath = app_path("Modules/{$name}/{$name}ServiceProvider.php");

        if (!file_exists($providerPath)) {
            $this->warn("⚠️ ServiceProvider not found at {$providerPath}");
            return;
        }

        $providerContent = file_get_contents($providerPath);

        $bindingCode = <<<PHP

        // Bind interfaces to implementations
        \$this->app->bind(
            \\App\\Modules\\{$name}\\Interfaces\\Repository\\{$name}RepositoryInterface::class,
            \\App\\Modules\\{$name}\\Repositories\\Eloquent{$name}Repository::class
        );

        \$this->app->bind(
            \\App\\Modules\\{$name}\\Interfaces\\Service\\{$name}ServiceInterface::class,
            \\App\\Modules\\{$name}\\Services\\{$name}Service::class
        );
PHP;

        if (!str_contains($providerContent, 'bind(')) {
            $providerContent = str_replace('//', $bindingCode, $providerContent);
            file_put_contents($providerPath, $providerContent);
            $this->info("🔗 Bindings added to {$name}ServiceProvider.");
        } else {
            $this->warn("⚠️ Bindings already exist inside {$name}ServiceProvider.");
        }
    }
}
