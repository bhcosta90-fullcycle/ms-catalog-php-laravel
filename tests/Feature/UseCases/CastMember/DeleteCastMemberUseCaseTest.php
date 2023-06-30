<?php

use App\Models\CastMember as Model;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\CastMember as UseCase;

test("testando a integração do caso de uso para deletar", function () {
    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\DeleteCastMemberUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\CastMemberInput(
        id: $domain->id,
    ));

    $this->assertSoftDeleted('cast_members', [
        'id' => $domain->id,
    ]);
});
