<?php

use App\Models\CastMember as Model;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\CastMember as UseCase;

test('testando a integração do caso de uso para listar o domínio quando estiver vazio', function () {
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\ListCastMembersUseCase(repository: $repository);
    $response = $useCase->execute();

    expect($response->total)->toBe(0);
    expect($response->items)->toHaveCount(0);
    expect($response->current_page)->toBe(1);
    expect($response->first_page)->toBe(0);
    expect($response->last_page)->toBe(1);
    expect($response->to)->toBe(0);
    expect($response->from)->toBe(0);
    expect($response->per_page)->toBe(15);
});

test('testando a integração do caso de uso para listar o domínio quando não estiver vazio', function () {
    Model::factory(20)->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\ListCastMembersUseCase(repository: $repository);
    $response = $useCase->execute();

    expect($response->total)->toBe(20);
    expect($response->items)->toHaveCount(15);
    expect($response->current_page)->toBe(1);
    expect($response->first_page)->toBe(1);
    expect($response->last_page)->toBe(2);
    expect($response->to)->toBe(1);
    expect($response->from)->toBe(15);
    expect($response->per_page)->toBe(15);
});
