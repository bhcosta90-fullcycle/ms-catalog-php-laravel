<?php

namespace App\Services\Storage;

use BRCas\CA\UseCase\FileStorageInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStorage implements FileStorageInterface
{
    public function store(string $path, array $file): string
    {
        $contents = $this->convertoFileToLaravelFile($file);
        return Storage::put($path, $contents);
    }

    public function delete(string $path): bool
    {
        return Storage::delete($path);
    }

    protected function convertoFileToLaravelFile(array $file): UploadedFile
    {
        return new UploadedFile(
            path: $file['tmp_name'],
            originalName: $file['name'],
            mimeType: $file['type'],
            error: $file['error'],
        );
    }
}
