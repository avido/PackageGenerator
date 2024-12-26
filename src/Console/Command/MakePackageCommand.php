<?php

namespace Avido\PackageGenerator\Console\Command;

use Avido\PackageGenerator\Generator\PackageGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\text;

class MakePackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:make {name?} {version?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Skeleton for new package';

    public function __construct(
        protected PackageGenerator $packageGenerator
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $name = trim($this->argument('name'));
            $version = trim($this->argument('version'));

            if (!$name) {
                $name = trim(text('Package name?'));
            }
            if (!$version) {
                $version = trim(text('Versie', '', '0.1.0'));
            }

            // check version
            $this->checkVersion($version);

            if (!$name) {
                throw new \Exception("Name is required");
            }

            $this->packageGenerator
                ->setConsole($this)
                ->setPackage($name)
                ->setVersion($version)
                ->generate();

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function checkVersion(string $version)
    {
        $validator = Validator::make(
            ['version' => $version],
            ['version' => ['required', 'regex:/^\d+\.\d+\.\d+$/']]
        );

        if ($validator->fails()) {
            throw new \Exception('The version is not valid. It should follow the Semantic Versioning (<major>.<minor>.<patch>).');
        }
    }
}
