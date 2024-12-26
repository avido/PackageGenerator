<?php

namespace Avido\PackageGenerator\Generator\Config;

abstract class BaseConfig
{
    protected array $folders = [];
    protected array $files = [];

    protected array $commands = [];

    public function getFolders(): array
    {
        return $this->folders;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
}
