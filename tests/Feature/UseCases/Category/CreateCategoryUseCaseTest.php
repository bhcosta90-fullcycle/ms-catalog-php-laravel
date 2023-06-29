<?php

use BRCas\MV\Domain\Repository\CategoryRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\Category as UseCase;

test("testando a integração do caso de uso para a criação", function(){
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\CreateCategoryUseCase(repository: $repository);
    $response = $useCase->execute(new UseCase\DTO\CreateCategory\Input(
        name: 'testing category',
    ));

    $this->assertDatabaseHas('categories', [
        'id' => $response->id,
        'name' => 'testing category',
    ]);
});
