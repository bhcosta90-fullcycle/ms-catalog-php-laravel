<?php

use App\Models\Video as Model;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\Video as UseCase;

test('testando a integração do caso de uso para deletar', function () {
    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\DeleteVideoUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\ListVideoInput(
        id: $domain->id,
    ));

    $this->assertSoftDeleted('videos', [
        'id' => $domain->id,
    ]);
});

test('testando a integração do caso de uso para deletar -> exception', function () {
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\DeleteVideoUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\ListVideoInput(
        id: '123',
    ));
})->throws(EntityNotFoundException::class);
