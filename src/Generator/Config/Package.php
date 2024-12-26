<?php

namespace Avido\PackageGenerator\Generator\Config;

use Avido\PackageGenerator\Concerns\Configuration;

class Package extends BaseConfig implements Configuration
{
    public function __construct()
    {
        $this->folders = [
            'actions'           => 'Actions',
            'config'            => 'Config',
            'command'           => 'Console/Commands',
            'contracts'         => 'Contracts',
            'factories'         => 'Database/Factories',
            'migration'         => 'Database/Migrations',
            'seeder'            => 'Database/Seeders',
            'dto'               => 'DTO',
            'enums'             => 'Enums',
            'event'             => 'Events',
            'exceptions'        => 'Exceptions',
            'routes'            => 'Http',
            'controller'        => 'Http/Controllers',
            'filter'            => 'Http/Middleware',
            'request'           => 'Http/Requests',
            'httpResource'      => 'Http/Resources',
            'httpRoutes'        => 'Http/Routes',
            'jobs'              => 'Jobs',
            'listener'          => 'Listeners',
            'emails'            => 'Mail',
            'model'             => 'Models',
            'provider'          => 'Providers',
            'lang'              => 'Resources/lang',
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
