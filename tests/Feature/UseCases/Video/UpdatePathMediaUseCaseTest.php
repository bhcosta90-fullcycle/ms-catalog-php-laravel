<?php

use App\Models\Video as ModelsVideo;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\MediaStatus;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use BRCas\MV\Domain\ValueObject\Media;
use BRCas\MV\UseCases\Video\UpdatePathMediaUseCase;
use BRCas\MV\UseCases\Video\DTO\UpdatePathMediaInput;
use BRCas\MV\UseCases\Video\DTO\VideoOutput;

beforeEach(function () {
    $video = ModelsVideo::factory()->create();

    $this->entity = new Video(
        id: $this->id = new Uuid($video->id),
        title: 'testing',
        description: 'description',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        createdAt: new DateTime($video->created_at),
    );
    $this->mockRepository = app(VideoRepositoryInterface::class);
});

test("I'm trying update a video without a media file", function () {
    $useCase = new UpdatePathMediaUseCase(
        repository: $this->mockRepository,
    );

    $useCase->execute(new UpdatePathMediaInput(
        id: $this->id,
        path: 'testing',
        type: 'video'
    ));

    $this->assertDatabaseCount('medias_video', 0);
});

test("I'm trying update a video with a media file", function () {
    $this->entity->setVideoFile(new Media(path: 'test', status: MediaStatus::PENDING));
    $this->mockRepository->updateMedia($this->entity);

    $useCase = new UpdatePathMediaUseCase(
        repository: $this->mockRepository,
    );

    $response = $useCase->execute(new UpdatePathMediaInput(
        id: $this->id,
        path: 'testing',
        type: 'video'
    ));

    $this->assertDatabaseHas('medias_video', [
        'video_id' => $this->id,
        'type' => 0,
        'media_status' => 1,
        'encoded_path' => 'testing',
    ]);
});

test("I'm trying update a trailer without a media file", function () {
    $useCase = new UpdatePathMediaUseCase(
        repository: $this->mockRepository,
    );

    $useCase->execute(new UpdatePathMediaInput(
        id: $this->id,
        path: 'testing',
        type: 'video'
    ));

    $this->assertDatabaseCount('medias_video', 0);
});

test("I'm trying update a trailer with a media file", function () {
    $this->entity->setTrailerFile(new Media(path: 'test', status: MediaStatus::PENDING));
    $this->mockRepository->updateMedia($this->entity);

    $useCase = new UpdatePathMediaUseCase(
        repository: $this->mockRepository,
    );

    $response = $useCase->execute(new UpdatePathMediaInput(
        id: $this->id,
        path: 'testing',
        type: 'trailer'
    ));

    expect($response)->toBeInstanceOf(VideoOutput::class);

    $this->assertDatabaseHas('medias_video', [
        'video_id' => $this->id,
        'type' => 1,
        'media_status' => 1,
        'encoded_path' => 'testing',
    ]);
});

test("exception -> not found video", function () {
    $useCase = new UpdatePathMediaUseCase(
        repository: $this->mockRepository,
    );

    $useCase->execute(new UpdatePathMediaInput(
        id: 'fake-id',
        path: 'testing',
        type: 'trailer'
    ));
})->throws(EntityNotFoundException::class);
