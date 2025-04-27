<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeFullModuleCommand extends Command
{
    protected $signature = 'make:full-module {name}';
    protected $description = 'Create a full module structure with model, controller, interfaces, repository, service, routes and service provider.';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        $this->info("Creating full module: {$name}");

        // Call the individual commands
        $this->call('make:module-model', [
            'name' => $name,
            '--migration' => true,
        ]);

        $this->call('make:module-controller', [
            'name' => $name,
            '--model' => $name,
            '--api' => true,
        ]);

        $this->call('make:module-interface', [
            'name' => "{$name}RepositoryInterface",
            '--type' => 'repository',
        ]);

        $this->call('make:module-interface', [
            'name' => "{$name}ServiceInterface",
            '--type' => 'service',
        ]);


        $this->call('make:module-repository', [
            'name' => $name,
        ]);

        $this->call('make:module-service', [
            'name' => $name,
        ]);

        $this->call('make:module-route', [
            'name' => $name,
            '--type' => 'web',
        ]);

        $this->call('make:module-route', [
            'name' => $name,
            '--type' => 'api',
        ]);

        $this->call('make:module-provider', [
            'name' => $name,
        ]);

        $this->info("✅ Full module {$name} created successfully!");
    }
}
