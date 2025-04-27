<?php

namespace Codedsultan\ModularScaffolder;

use Illuminate\Support\ServiceProvider;

class ModularScaffolderServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register commands only if running in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\MakeFullModuleCommand::class,
                Commands\ModuleInstallCommand::class,
                Commands\ModuleInstallFrontendCommand::class,
                Commands\MakeModuleModelCommand::class,
                Commands\MakeModuleControllerCommand::class,
                Commands\MakeModuleServiceCommand::class,
                Commands\MakeModuleRepositoryCommand::class,
                Commands\MakeModuleResourceCommand::class,
                Commands\MakeModuleCollectionCommand::class,
                Commands\MakeModuleCrudTestCommand::class,
                Commands\MakeModuleFactoryCommand::class,
                // ... add all other command classes here
            ]);
        }
    }

    public function boot()
    {
        //
    }
}
