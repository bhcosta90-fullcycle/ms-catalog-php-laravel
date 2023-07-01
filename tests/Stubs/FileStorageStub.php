<?php

namespace Tests\Stubs;

use BRCas\CA\UseCase\FileStorageInterface;

class FileStorageStub implements FileStorageInterface
{
    /**
     * @param string $path
     * @param array $_FILES[file]
     * @return string
     */
    public function store(string $path, array $file): string
    {
        return "{$path}/{$file["name"]}";
    }

    public function delete(string $path): bool
    {
        return true;
    }
}
