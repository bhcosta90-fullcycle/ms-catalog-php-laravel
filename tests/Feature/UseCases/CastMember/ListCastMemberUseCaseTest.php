<?php

use App\Models\CastMember as Model;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\CastMember as UseCase;

test('testando a integração do caso de uso para buscar o domínio', function () {
    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\ListCastMemberUseCase(repository: $repository);
    $response = $useCase->execute(new UseCase\DTO\CastMemberInput(
        id: $domain->id,
    ));

    $this->assertDatabaseHas('cast_members', [
        'id' => $response->id,
    ]);
});

test('testando a integração do caso de uso para buscar o domínio -> exception', function () {
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\ListCastMemberUseCase(repository: $repository);
    $useCase->execute(new UseCase\DTO\CastMemberInput(
        id: 'fake-id',
    ));
})->throws(EntityNotFoundException::class);
