<?php

use App\Models\Genre as Model;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\Genre as UseCase;

test("testando a integração do caso de uso para deletar", function () {
    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\DeleteGenreUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\GenreInput(
        id: $domain->id,
    ));

    $this->assertSoftDeleted('genres', [
        'id' => $domain->id,
    ]);
});
