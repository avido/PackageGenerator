<?php

namespace Avido\PackageGenerator\Console\Command;

use Illuminate\Console\Command;

class MakeServiceProviderCommand extends BaseMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:make-service-provider {name} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service Provider';

    /**
     * @return string
     */
    protected function getStubContents(): string
    {
        return $this->packageGenerator->getStubContents('ServiceProvider', $this->getStubVariables());
    }

    /**
     * @return array
     */
    protected function getStubVariables(): array
    {
        return [
            'NAMESPACE'  => $this->getClassNamespace($this->argument('name') . '/Providers'),
            'CLASS'      => $this->getClassName($this->argument('name')) . "ServiceProvider",
            'LOWER_NAME' => $this->getLowerName($this->argument('name')),
        ];
    }

    /**
     * @return string
     */
    protected function getSourceFilePath(): string
    {
        $name = $this->argument('name');
        return base_path(
            "app/Modules/{$name}/src/Providers/{$this->getClassName($name)}ServiceProvider.php"
        );
    }
}
