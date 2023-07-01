<?php

use App\Models\Video as Model;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\Video as UseCase;

test("testando a integração do caso de uso para buscar o domínio", function () {
    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\ListVideoUseCase(repository: $repository);
    $response = $useCase->execute(new UseCase\DTO\ListVideoInput(
        id: $domain->id,
    ));

    $this->assertDatabaseHas('videos', [
        'id' => $response->id,
    ]);
});

test("testando a integração do caso de uso para buscar o domínio -> exception", function () {
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\ListVideoUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\ListVideoInput(
        id: 'fake-id',
    ));
})->throws(EntityNotFoundException::class);
