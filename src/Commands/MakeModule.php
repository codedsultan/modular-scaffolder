<?php

namespace CodedSultan\ModularScaffolder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModule extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create a new modular structure';

    public function handle()
    {
        $moduleName = ucfirst($this->argument('name'));
        // mkdir resources/js/Modules/Product/Pages
        // mkdir resources/js/Modules/Product/Components
        // mkdir resources/js/Modules/Product/Hooks
        // mkdir resources/js/Modules/Product/Services
        $paths = [
            "app/Modules/$moduleName/Controllers",
            "app/Modules/$moduleName/Models",
            "app/Modules/$moduleName/Requests",
            "app/Modules/$moduleName/Services",
            "routes/modules",

            "resources/js/Modules/$moduleName/Pages",
            "resources/js/Modules/$moduleName/Components",
            "resources/js/Modules/$moduleName/Hooks",
            "resources/js/Modules/$moduleName/Services",


        ];

        // php artisan make:model Modules/Product/Models/Product -m
        // php artisan make:controller Modules/Product/Controllers/ProductController --api --model=Modules/Product/Models/Product
        # Navigate to the correct directory first if needed
        // touch resources/js/Modules/Product/Pages/Index.tsx

        // php artisan make:module-model Product --migration
        // php artisan make:module-controller Product --model=Product --api

        $this->info("Creating module $moduleName...");

        foreach ($paths as $path) {
            File::ensureDirectoryExists(base_path($path));
        }

        File::put(base_path("routes/modules/".strtolower($moduleName).".php"), "<?php\n\n// API Routes for $moduleName");

        $this->info("Module $moduleName created successfully!");
    }
}
