<?php

namespace Avido\PackageGenerator\Concerns;

interface Configuration
{
    public function getFolders(): array;
    public function getFiles(): array;
    public function getCommands(): array;
}
