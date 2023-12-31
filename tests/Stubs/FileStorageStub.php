<?php

namespace Tests\Stubs;

use BRCas\CA\UseCase\FileStorageInterface;

class FileStorageStub implements FileStorageInterface
{
    /**
     * @param  array  $_FILES[file]
     */
    public function store(string $path, array $file): string
    {
        event(self::class);

        return "{$path}/{$file['name']}";
    }

    public function delete(string $path): bool
    {
        event(self::class);

        return true;
    }
}
