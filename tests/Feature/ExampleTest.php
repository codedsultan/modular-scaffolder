<?php

namespace Codedsultan\ModularScaffolder\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Codedsultan\ModularScaffolder\ModularScaffolderServiceProvider;

class ExampleTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ModularScaffolderServiceProvider::class,
        ];
    }

    public function test_example()
    {
        $this->assertTrue(true);
    }
}
