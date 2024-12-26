<?php

namespace Avido\PackageGenerator\Console\Command;

use Illuminate\Console\Command;

class MakeModuleServiceProviderCommand extends BaseMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:make-module-service-provider {name} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Module Service Provider';

    /**
     * @return string
     */
    protected function getStubContents(): string
    {
        return $this->packageGenerator->getStubContents('ModuleServiceProvider', $this->getStubVariables());
    }

    /**
     * @return array
     */
    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE'  => $this->getClassNamespace($this->argument('name') . '/Providers'),
            'CLASS'      => "ModuleServiceProvider",
        ];
    }

    /**
     * @return string
     */
    protected function getSourceFilePath(): string
    {
        return base_path(
            "app/Modules/{$this->argument('name')}/src/Providers/ModuleServiceProvider.php"
        );
    }
}
