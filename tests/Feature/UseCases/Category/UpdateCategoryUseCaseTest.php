<?php

use App\Models\Category as Model;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\Category as UseCase;

test("testando a integração do caso de uso para editar", function () {
    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\UpdateCategoryUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\UpdateCategory\Input(
        id: $domain->id,
        name: 'testing',
    ));

    $this->assertDatabaseHas('categories', [
        'id' => $domain->id,
        'name' => 'testing'
    ]);
});
