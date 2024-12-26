<?php

namespace Avido\PackageGenerator;

use Illuminate\Filesystem\Filesystem;

class Package
{
    public function __construct(protected Filesystem $filesystem)
    {
    }

    /**
     * Checks if package exist or not
     *
     * @return bool
     */
    public function exists(string $package)
    {
        return $this->filesystem->isDirectory(base_path("app/Modules/{$package}"));
    }

    /**
     * Deletes specific package
     *
     * @return void
     */
    public function delete(string $package)
    {
        $this->filesystem->deleteDirectory(base_path("app/Modules/{$package}"));
    }
}
