<?php

namespace Avido\PackageGenerator\Generator;

use Avido\PackageGenerator\Concerns\Configuration;
use Avido\PackageGenerator\Generator\Config\ConfigurationManager;
use Avido\PackageGenerator\Package;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class PackageGenerator
{
    private $console;

    private string $version;

    private string $packageName;
    private string $packageFriendlyName;

    public Configuration $configuration;

    public function __construct(
        private Package $package,
        private Filesystem $filesystem,
        public ConfigurationManager $configurationManager
    ) {
        $this->configuration = $this->configurationManager->driver($this->configurationManager->getDefaultDriver());
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function setPackage(string $packageName)
    {
        $this->packageFriendlyName = Str::kebab($packageName);
        $this->packageName = Str::studly($packageName);

        return $this;
    }

    /**
     * Set console.
     */
    public function setConsole($console)
    {
        $this->console = $console;

        return $this;
    }

    public function generate()
    {
        if ($this->package->exists($this->packageName)) {
            $this->console->error("Package '{$this->packageName}' already exist !");
        } else {
            $this->createFolders();

            $this->createStubs();

            // create manifest file
            $this->createManifest();

            // create readme
            $this->createReadme();

            // create composer
            $this->createComposer();

            // register it
            $this->registerModule();
            $this->console->info("Package '{$this->packageName}' created successfully.");
        }
    }

    private function registerModule()
    {
        $this->console->info("Register package in main composer file");

        $composerFilePath = './composer.json';

        if (!file_exists($composerFilePath)) {
            $this->console->error("No main composer file");
            return ;
        }
        $composerJson = json_decode(file_get_contents($composerFilePath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->console->error("Error decoding composer.json: " . json_last_error_msg() . "\n");
            return ;
        }

        if (!isset($composerJson['repositories'])) {
            $composerJson['repositories'] = [];
        }

        $repositoryExists = false;

        foreach ($composerJson['repositories'] as $repository) {
            if (isset($repository['url']) && $repository['url'] === "App/Modules/*") {
                $repositoryExists = true;
                break;
            }
        }

        if (!$repositoryExists) {
            $composerJson['repositories'][] = [
                "type" => "path",
                "url" => "App/Modules/{$this->packageName}"
            ];
        }

        $composerJson['require']["module/$this->packageFriendlyName"] = $this->version;

        if (isset($composerJson['repositories']) && isset($composerJson['require'])) {
            $repositories = $composerJson['repositories'];
            unset($composerJson['repositories']);

            $composerJson = array_merge(
                array_slice($composerJson, 0, array_search('require', array_keys($composerJson))), // alles tot en met require
                ['repositories' => $repositories], // voeg repositories toe
                array_slice($composerJson, array_search('require', array_keys($composerJson))) // alles vanaf require
            );
        }

        $newComposerJson = json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->console->error("Error encoding composer.json: " . json_last_error_msg() . "\n");
            return ;
        }

        file_put_contents($composerFilePath, $newComposerJson);

        $this->console->line("→ 'package {$this->packageName} successfully added to maim composer.json");
    }

    private function createFolders(): void
    {
        $this->console->info("Create folder structure");
        foreach ($this->configuration->getFolders() as $key => $folder) {
            $path = base_path("App/Modules/{$this->packageName}/src/{$folder}");
            if ($this->filesystem->isDirectory($path)) {
                $this->console->error("{$path} bestaat reeds, skipping..");
            }
            $this->filesystem->makeDirectory($path, 0755, true);
            $this->console->line("→ '{$folder}' created!");
        }
    }

    private function createStubs(): void
    {
        $this->console->info("Create stub files");

        foreach ($this->configuration->getCommands() as $command => $arguments) {
            if (is_string($arguments)) {
                $command = $arguments;
                $arguments = [];
            }

            $arguments = [
                'name' => $this->packageName,
                ...$arguments
            ];

            $this->console->call("{$command}", $arguments);
            $this->console->line("→ '{$command}' executed!");
        }
    }

    private function createManifest(): void
    {
        $this->console->info("Create package manifest");
        $path = "app/Modules/{$this->packageName}/src/Resources/manifest.php";
        $contents = $this->getStubContents('Manifest', [
            'name' => $this->packageFriendlyName,
            'version' => $this->version
        ]);
        if (!$this->filesystem->exists($path)) {
            $this->filesystem->put($path, $contents);
            $this->console->line("→ 'Manifest file generated");
        }
    }

    private function createReadme(): void
    {
        $this->console->info("Create package readme");
        $path = "app/Modules/{$this->packageName}/README.md";
        $contents = $this->getStubContents('Readme', [
            'name' => $this->packageFriendlyName,
            'version' => $this->version
        ]);
        if (!$this->filesystem->exists($path)) {
            $this->filesystem->put($path, $contents);
            $this->console->line("→ 'Readme file generated");
        }
    }

    private function createComposer(): void
    {
        $this->console->info("Create package composer");
        $path = "app/Modules/{$this->packageName}/composer.json";
        $contents = $this->getStubContents('Composer', [
            'name' => $this->packageFriendlyName,
            'classname' => class_basename($this->packageName),
            'version' => $this->version,
            'NAMESPACE'  => 'App\\\\Modules\\\\' . $this->packageName . '\\\\',
        ]);

        if (!$this->filesystem->exists($path)) {
            $this->filesystem->put($path, $contents);
            $this->console->line("→ 'composer file generated");
        }
    }

    /**
     * @param string $stub
     * @param array $variables
     * @return string
     */
    public function getStubContents(string $stub, array $variables = []): string
    {
        $contents = file_get_contents(__DIR__ . "/../Stubs/{$stub}.stub");

        foreach ($variables as $search => $replace) {
            $contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
        }

        return $contents;
    }
}
