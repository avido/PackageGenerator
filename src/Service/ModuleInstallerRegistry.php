<?php

declare(strict_types=1);

namespace Avido\PackageGenerator\Service;

use Avido\PackageGenerator\Concerns\ModuleInstaller;

class ModuleInstallerRegistry
{

    protected static array $installers = [];

    public static function register(string $module, string $installerClass): void
    {
        if (!class_exists($installerClass)) {
            throw new \Exception("Installer class {$installerClass} not found.");
        }

        if (!is_subclass_of($installerClass, ModuleInstaller::class)) {
            throw new \Exception("{$installerClass} must inherit ModuleInstaller interface.");
        }

        self::$installers[$module] = $installerClass;
    }

    public static function getInstaller(string $module): null | ModuleInstaller
    {
        return isset(self::$installers[$module])
            ? app(self::$installers[$module])
            : null;
    }

    public static function getAllInstallers(): array
    {
        return self::$installers;
    }
}
