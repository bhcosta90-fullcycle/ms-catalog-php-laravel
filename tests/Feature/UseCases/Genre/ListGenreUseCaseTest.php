<?php

use App\Models\Genre as Model;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\Genre as UseCase;

test('testando a integração do caso de uso para buscar o domínio', function () {
    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\ListGenreUseCase(repository: $repository);
    $response = $useCase->execute(new UseCase\DTO\GenreInput(
        id: $domain->id,
    ));

    $this->assertDatabaseHas('genres', [
        'id' => $response->id,
    ]);
});

test('testando a integração do caso de uso para buscar o domínio -> exception', function () {
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\ListGenreUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\GenreInput(
        id: 'fake-id',
    ));
})->throws(EntityNotFoundException::class);
