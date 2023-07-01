<?php

use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\CastMember as UseCase;

test("testando a integração do caso de uso para a criação", function(){
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\CreateCastMemberUseCase(repository: $repository);
    $response = $useCase->execute(new UseCase\DTO\CreateCastMember\Input(
        name: 'testing cast member',
        type: 2
    ));

    $this->assertDatabaseHas('cast_members', [
        'id' => $response->id,
        'name' => 'testing cast member',
    ]);
});
