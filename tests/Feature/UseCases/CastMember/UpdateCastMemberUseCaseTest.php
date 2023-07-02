<?php

use App\Models\CastMember as Model;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\CastMember as UseCase;

test('testando a integração do caso de uso para editar', function () {
    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\UpdateCastMemberUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\UpdateCastMember\Input(
        id: $domain->id,
        name: 'testing',
    ));

    $this->assertDatabaseHas('cast_members', [
        'id' => $domain->id,
        'name' => 'testing',
    ]);
});
