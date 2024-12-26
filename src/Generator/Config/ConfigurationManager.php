<?php

namespace Avido\PackageGenerator\Generator\Config;

use Illuminate\Support\Manager;

class ConfigurationManager extends Manager
{
    public function getDefaultDriver()
    {
        // Define the default configuration type, if any
        return 'package';
    }

    protected function createPackageDriver()
    {
        return new Package();
    }
}
