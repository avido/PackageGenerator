<?php

declare(strict_types=1);

namespace Avido\PackageGenerator\Console\Command;

use Avido\PackageGenerator\Service\ModuleInstallerRegistry;
use Illuminate\Console\Command;

class InstallModule extends Command
{
    protected string $signature = 'package:install {module?}';
    protected string $description = 'Run module install';

    public function handle()
    {
        $module = $this->argument('module');

        if ($module) {
            $this->installModule($module);
        } else {
            foreach (ModuleInstallerRegistry::getAllInstallers() as $name => $installerClass) {
                $this->installModule($name);
            }
        }
    }

    protected function installModule(string $module)
    {
        $installer = ModuleInstallerRegistry::getInstaller($module);

        if (!$installer) {
            $this->error("No installer found for: '{$module}'.");
            return;
        }

        $this->info("Installing module '{$module}'...");
        if ($installer instanceof \Closure) {
            $installer();
        } else {
            $installer->install();
        }
        $this->info("Module '{$module}' successfully installed.");
    }
}
