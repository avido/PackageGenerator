<?php

namespace Avido\PackageGenerator\Generator\Config;

use Avido\PackageGenerator\Concerns\Configuration;

class Package extends BaseConfig implements Configuration
{
    public function __construct()
    {
        $this->folders = [
            'actions'           => 'src/Actions',
            'config'            => 'src/Config',
            'command'           => 'src/Console/Commands',
            'contracts'         => 'src/Contracts',
            'factories'         => 'src/Database/Factories',
            'migration'         => 'src/Database/Migrations',
            'seeder'            => 'src/Database/Seeders',
            'dto'               => 'src/DTO',
            'enums'             => 'src/Enums',
            'event'             => 'src/Events',
            'exceptions'        => 'src/Exceptions',
            'routes'            => 'src/Http',
            'controller'        => 'src/Http/Controllers',
            'filter'            => 'src/Http/Middleware',
            'request'           => 'src/Http/Requests',
            'httpResource'      => 'src/Http/Resources',
            'httpRoutes'        => 'src/Http/Routes',
            'jobs'              => 'src/Jobs',
            'listener'          => 'src/Listeners',
            'emails'            => 'src/Mail',
            'model'             => 'src/Models',
            'provider'          => 'src/Providers',
            'lang'              => 'src/Resources/lang',
            'tests'             => 'tests',
            'tests_eloquent'    => 'tests/Feature/Eloquent',
            'tests_http'        => 'tests/Http',
        ];

        $this->files = [
            ''
        ];

        $this->commands = [
            'package:make-service-provider',
            'package:make-module-service-provider',
        ];
    }
}
