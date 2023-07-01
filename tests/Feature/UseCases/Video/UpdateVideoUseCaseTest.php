<?php

use App\Models\CastMember;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use BRCas\CA\UseCase\DatabaseTransactionInterface;
use BRCas\CA\UseCase\FileStorageInterface;
use BRCas\MV\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use BRCas\MV\UseCases\Video as UseCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Stubs\VideoEventManagerStub;

beforeEach(function(){
    Storage::fake();
    $this->model = Video::factory()->create();
});

test("criação de um vídeo com os relacionamentos", function ($data) {
    if (!empty($data['categories'])) {
        $categories = Category::factory($data['categories'])->create()
            ->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    }

    if (!empty($data['genres'])) {
        $genres = Genre::factory($data['genres'] ?? [])->create()
            ->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    }

    if (!empty($data['cast-members'])) {
        $castMembers = CastMember::factory($data['cast-members'] ?? [])->create()
            ->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    }


    $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
    $file = converteUploadFile($fakeFile);

    $useCase = new UseCase\UpdateVideoUseCase(
        app(VideoRepositoryInterface::class),
        app(CategoryRepositoryInterface::class),
        app(CastMemberRepositoryInterface::class),
        app(GenreRepositoryInterface::class),
        app(DatabaseTransactionInterface::class),
        app(FileStorageInterface::class),
        new VideoEventManagerStub,
    );

    $response = $useCase->execute(new UseCase\DTO\UpdateVideoInput(
        id: $this->model->id,
        title: 'title',
        description: 'description',
        categories: $categories ?? [],
        genres: $genres ?? [],
        castMembers: $castMembers ?? [],
        videoFile: !empty($data['video-file']) ? $file : null,
        trailerFile: !empty($data['trailer-file']) ? $file : null,
        bannerFile: !empty($data['banner-file']) ? $file : null,
        thumbFile: !empty($data['thumb-file']) ? $file : null,
        thumbHalf: !empty($data['half-file']) ? $file : null,
    ));

    expect($response->id)->not->toBeNull();
    expect($response->title)->toBe('title');
    expect($response->description)->toBe('description');
    expect($response->year_launched)->toBe($this->model->year_launched);
    expect($response->duration)->toBe($this->model->duration);
    expect($response->rating)->toBe($this->model->rating);
    expect($response->categories)->toHaveCount(count($categories ?? []));
    expect($response->genres)->toHaveCount(count($genres ?? []));
    expect($response->cast_members)->toHaveCount(count($castMembers ?? []));
    expect(!empty($data['video-file']) ? $response->video_file != null : $response->video_file === null)->toBeTrue();
    expect(!empty($data['trailer-file']) ? $response->trailer_file != null : $response->trailer_file === null)->toBeTrue();
    expect(!empty($data['banner-file']) ? $response->banner_file != null : $response->banner_file === null)->toBeTrue();
    expect(!empty($data['thumb-file']) ? $response->thumb_file != null : $response->thumb_file === null)->toBeTrue();
    expect(!empty($data['half-file']) ? $response->thumb_half != null : $response->thumb_half === null)->toBeTrue();
    expect($response->created_at)->not->toBeNull();
})->with([
    "only-categories" => fn () => [
        'categories' => 4,
    ],
    "only-genres" => fn () => [
        'genres' => 2,
    ],
    "only-cast-members" => fn () => [
        'cast-members' => 3,
    ],
    "all-relationship" => fn () => [
        'categories' => 4,
        'cast-members' => 3,
        'genres' => 2,
    ], "with-video-file" => fn () => [
        'video-file' => true,
    ],
    "with-trailer-file" => fn () => [
        'trailer-file' => true,
    ],
    "with-banner-file" => fn () => [
        'banner-file' => true,
    ],
    "with-thumb-file" => fn () => [
        'thumb-file' => true,
    ],
    "with-half-file" => fn () => [
        'half-file' => true,
    ],
    "with-all-medias" => fn() => [
        'video-file' => true,
        'trailer-file' => true,
        'banner-file' => true,
        'thumb-file' => true,
        'half-file' => true,
    ],
    "with-all-data" => fn() => [
        'categories' => 4,
        'cast-members' => 3,
        'genres' => 2,
        'video-file' => true,
        'trailer-file' => true,
        'banner-file' => true,
        'thumb-file' => true,
        'half-file' => true,
    ],
]);
