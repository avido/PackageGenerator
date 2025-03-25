<?php

declare(strict_types=1);

namespace Avido\PackageGenerator\Service;

use Avido\PackageGenerator\Concerns\ModuleInstaller;

class ModuleInstallerRegistry
{

    protected static array $installers = [];

    public static function register(string $module, string $installer): void
    {
        if ($installer instanceof \Closure) {
            // Store the closure directly
            self::$installers[$module] = $installer;
        } elseif (is_string($installer) && class_exists($installer) && is_subclass_of($installer, ModuleInstaller::class)) {
            self::$installers[$module] = $installer;
        } else {
            throw new \Exception("Installer class {$installer} not found.");
        }
    }

    public static function getInstaller(string $module): null | \Closure | ModuleInstaller
    {
        return self::$installers[$module] ?? null;
    }

    public static function getAllInstallers(): array
    {
        return self::$installers;
    }
}
