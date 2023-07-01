<?php

use BRCas\CA\UseCase\FileStorageInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test("store", function (){
    $fakeFile = UploadedFile::fake()->create('video.mp', 1, 'video/mp4');

    $file = [
        'tmp_name' => $fakeFile->getPathname(),
        'name' => $fakeFile->getClientOriginalName(),
        'type' => $fakeFile->getType(),
        'error' => $fakeFile->getError(),
    ];

    /**
     * @var FileStorageInterface
     */
    $fileStore = app(FileStorageInterface::class);
    $path = $fileStore->store('videos', $file);

    Storage::assertExists($path);
    Storage::delete($path);
});

test("delete", function(){
    $file = UploadedFile::fake()->create('video.mp', 1, 'video/mp4');
    $path = $file->store('videos');

    /**
     * @var FileStorageInterface
     */
    $fileStore = app(FileStorageInterface::class);
    expect($fileStore->delete($path))->toBeTrue();

    Storage::assertMissing($path);
});
