<?php

use App\Models\Category as Model;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\Category as UseCase;

test('testando a integração do caso de uso para deletar', function () {
    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\DeleteCategoryUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\CategoryInput(
        id: $domain->id,
    ));

    $this->assertSoftDeleted('categories', [
        'id' => $domain->id,
    ]);
});

test('testando a integração do caso de uso para deletar -> exception', function () {
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\DeleteCategoryUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\CategoryInput(
        id: 'fake-id',
    ));
})->throws(EntityNotFoundException::class);
