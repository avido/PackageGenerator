<?php

namespace Avido\PackageGenerator\Console\Command;

use Avido\PackageGenerator\Generator\PackageGenerator;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

abstract class BaseMakeCommand extends Command
{
    public function __construct(
        protected Filesystem $filesystem,
        protected PackageGenerator $packageGenerator
    ) {
        parent::__construct();
    }

    abstract protected function getSourceFilePath();
    abstract protected function getStubContents();

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $path = $this->getSourceFilePath();

        if (!$this->filesystem->isDirectory($dir = dirname($path))) {
            $this->filesystem->makeDirectory($dir, 0775, true);
        }

        $contents = $this->getStubContents();

        if (!$this->filesystem->exists($path)) {
            $this->filesystem->put($path, $contents);
        } else {
            if ($this->option('force')) {
                $this->filesystem->put($path, $contents);
            } else {
                $this->error("File : {$path} already exists.");

                return;
            }
        }

        $this->info("File Created : {$path}");
    }

    /**
     * Get Package Name in Lower case.
     * @param string $name
     * @return string
     */
    protected function getLowerName(string $name): string
    {
        return strtolower(class_basename($name));
    }

    /**
     * Get Class Name.
     * @param string $name
     * @return string
     */
    protected function getClassName(string $name): string
    {
        return class_basename($name);
    }

    /**
     * Get NameSpace for Controller.
     * @param string $name
     * @return string
     */
    protected function getClassNamespace(string $name): string
    {
        $ns = "App\\Modules\\{$name}";

        return str_replace('/', '\\', $ns);
    }
}
