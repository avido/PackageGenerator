<?php

namespace Avido\PackageGenerator\Providers;

use Avido\PackageGenerator\Console\Command\MakeModuleServiceProviderCommand;
use Avido\PackageGenerator\Console\Command\MakePackageCommand;
use Avido\PackageGenerator\Console\Command\MakeServiceProviderCommand;
use Illuminate\Support\ServiceProvider;

class PackageGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerCommands();
    }

    /**
     * Register the console commands of this package.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakePackageCommand::class,
                MakeServiceProviderCommand::class,
                MakeModuleServiceProviderCommand::class,
            ]);
        }
    }
}
