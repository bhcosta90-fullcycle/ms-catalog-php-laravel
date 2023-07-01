<?php

use App\Models\CastMember;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video as Model;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->categories = Category::factory(2)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    $this->genres = Genre::factory(3)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    $this->castMembers = CastMember::factory(4)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();

    $this->endpoint = "/videos";
    $this->serializeFields = [
        'id',
        'title',
        'description',
        'year_launched',
        'duration',
        'opened',
        'rating',
        'created_at',
    ];
});

test("listando todas os vídeos quando está vazio", function () {
    $response = $this->get($this->endpoint);
    $response->assertOk();
    $response->assertJsonCount(0, 'data');
});

test("listando todas do vídeo", function () {
    Model::factory(50)->create();
    $response = $this->get($this->endpoint);
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            '*' => $this->serializeFields,
        ],
        'meta' => [
            'total',
            'current_page',
            'last_page',
            'first_page',
            'per_page',
            'to',
            'from',
        ],
    ]);
    $response->assertJsonCount(15, 'data');
});

test("listando a quarta página dos registros", function () {
    Model::factory(50)->create();
    $response = $this->get($this->endpoint . '?page=4');
    $response->assertOk();
    $this->assertEquals(4, $response['meta']['current_page']);
    $this->assertEquals(50, $response['meta']['total']);
    $response->assertJsonCount(5, 'data');
});

test("buscando um vídeo", function () {
    $video = Model::factory()->create();
    $response = $this->get($this->endpoint . '/' . $video->id);
    $response->assertOk()->assertJsonStructure([
        'data' => $this->serializeFields
    ]);
});

test("buscando um vídeo -> exception", function () {
    $response = $this->get($this->endpoint . '/fake-id');
    $response->assertNotFound();
});

test("cadastrando um video simples", function () {
    $response = $this->postJson($this->endpoint, [
        'title' => 'title',
        'description' => 'description',
        'year_launched' => 2010,
        'duration' => 50,
        'opened' => true,
        'rating' => 'L',
        'categories' => $this->categories,
        'genres' => $this->genres,
        'cast_members' => $this->castMembers,
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'data' => $this->serializeFields,
        ]);

    $this->assertDatabaseHas('videos', [
        'id' => $response->json('data.id'),
    ]);
});

test("cadastrando com todos os dados", function () {
    $videoFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
    $trailerFile = UploadedFile::fake()->create('trailer.mp4', 1, 'video/mp4');
    $bannerFile = UploadedFile::fake()->create('banner.png', 1, 'image/png');
    $thumbFile = UploadedFile::fake()->create('thumb.png', 1, 'image/png');
    $halfFile = UploadedFile::fake()->create('half.png', 1, 'image/png');

    $response = $this->postJson($this->endpoint, [
        'title' => 'title',
        'description' => 'description',
        'year_launched' => 2010,
        'duration' => 50,
        'opened' => true,
        'rating' => 'L',
        'video_file' => $videoFile,
        'trailer_file' => $trailerFile,
        'banner_file' => $bannerFile,
        'thumb_file' => $thumbFile,
        'half_file' => $halfFile,
        'categories' => $this->categories,
        'genres' => $this->genres,
        'cast_members' => $this->castMembers,
    ]);

    expect($response->json('data.categories'))->toBe($this->categories);
    expect($response->json('data.genres'))->toBe($this->genres);
    expect($response->json('data.cast_members'))->toBe($this->castMembers);

    Storage::assertExists($response->json('data.video_file'));
    Storage::assertExists($response->json('data.trailer_file'));
    Storage::assertExists($response->json('data.banner_file'));
    Storage::assertExists($response->json('data.thumb_file'));
    Storage::assertExists($response->json('data.thumb_half'));

    Storage::deleteDirectory('videos/' . $response->json('data.id'));
});

test("validated", function () {
    $response = $this->postJson($this->endpoint, []);
    $response->assertUnprocessable();
    $response->assertJsonValidationErrors([
        'title',
        'description',
        'year_launched',
        'duration',
        'opened',
        'rating',
        'categories',
        'genres',
        'cast_members',
    ]);
});

test("editando um vídeo com todos os dados", function () {
    $model = Video::factory()->create();

    $videoFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
    $trailerFile = UploadedFile::fake()->create('trailer.mp4', 1, 'video/mp4');
    $bannerFile = UploadedFile::fake()->create('banner.png', 1, 'image/png');
    $thumbFile = UploadedFile::fake()->create('thumb.png', 1, 'image/png');
    $halfFile = UploadedFile::fake()->create('half.png', 1, 'image/png');

    $response = $this->putJson($this->endpoint . '/' . $model->id, [
        'title' => 'title',
        'description' => 'description',
        'video_file' => $videoFile,
        'trailer_file' => $trailerFile,
        'banner_file' => $bannerFile,
        'thumb_file' => $thumbFile,
        'half_file' => $halfFile,
        'categories' => $this->categories,
        'genres' => $this->genres,
        'cast_members' => $this->castMembers,
    ]);

    expect($response->json('data.categories'))->toBe($this->categories);
    expect($response->json('data.genres'))->toBe($this->genres);
    expect($response->json('data.cast_members'))->toBe($this->castMembers);

    Storage::assertExists($response->json('data.video_file'));
    Storage::assertExists($response->json('data.trailer_file'));
    Storage::assertExists($response->json('data.banner_file'));
    Storage::assertExists($response->json('data.thumb_file'));
    Storage::assertExists($response->json('data.thumb_half'));

    Storage::deleteDirectory('videos/' . $response->json('data.id'));
});

test("editando um vídeo com todos os dados -> exception", function () {
    $response = $this->putJson($this->endpoint . '/fake-id', [
        'title' => 'title',
        'description' => 'description',
        'categories' => $this->categories,
        'genres' => $this->genres,
        'cast_members' => $this->castMembers,
    ]);
    $response->assertNotFound();
});

test("deletando um vídeo com todos os dados", function () {
    $model = Video::factory()->create();
    $response = $this->deleteJson($this->endpoint . '/' . $model->id);
    $response->assertNoContent();
});

test("deletando um vídeo com todos os dados -> exception", function () {
    $response = $this->deleteJson($this->endpoint . '/fake-id');

    $response->assertNotFound();
});
