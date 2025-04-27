<?php

namespace Codedsultan\ModularScaffolder\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Codedsultan\ModularScaffolder\ModularScaffolderServiceProvider;
use Illuminate\Support\Facades\File;

class ModuleInstallTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ModularScaffolderServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Clean up test modules folder if exists
        if (File::exists(app_path('Modules/TestProduct'))) {
            File::deleteDirectory(app_path('Modules/TestProduct'));
        }
    }

    /** @test */
    public function it_can_run_the_module_install_command()
    {
        $this->artisan('module:install', ['name' => 'TestProduct'])
            ->expectsOutput('✅ Module TestProduct fully installed successfully!')
            ->assertExitCode(0);

        // Assert folder was created
        $this->assertDirectoryExists(app_path('Modules/TestProduct/Controllers'));
        $this->assertDirectoryExists(app_path('Modules/TestProduct/Models'));
        $this->assertDirectoryExists(app_path('Modules/TestProduct/Services'));
        $this->assertDirectoryExists(app_path('Modules/TestProduct/Repositories'));
        $this->assertFileExists(app_path('Modules/TestProduct/TestProductServiceProvider.php'));
    }

    protected function tearDown(): void
    {
        // Clean up after test
        if (File::exists(app_path('Modules/TestProduct'))) {
            File::deleteDirectory(app_path('Modules/TestProduct'));
        }

        parent::tearDown();
    }
}
