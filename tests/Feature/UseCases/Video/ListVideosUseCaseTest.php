<?php

use App\Models\Video as Model;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\Video as UseCase;

test('testando a integração do caso de uso para listar o domínio quando estiver vazio', function () {
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\ListVideosUseCase(repository: $repository);
    $response = $useCase->execute();

    expect($response)->toBeInstanceOf(PaginateInterface::class);

    expect($response->total())->toBe(0);
    expect($response->items())->toHaveCount(0);
    expect($response->currentPage())->toBe(1);
    expect($response->firstPage())->toBe(0);
    expect($response->lastPage())->toBe(1);
    expect($response->to())->toBe(0);
    expect($response->from())->toBe(0);
    expect($response->perPage())->toBe(15);
});

test('testando a integração do caso de uso para listar o domínio quando não estiver vazio', function () {
    Model::factory(20)->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\ListVideosUseCase(repository: $repository);
    $response = $useCase->execute();

    expect($response->total())->toBe(20);
    expect($response->items())->toHaveCount(15);
    expect($response->currentPage())->toBe(1);
    expect($response->firstPage())->toBe(1);
    expect($response->lastPage())->toBe(2);
    expect($response->to())->toBe(1);
    expect($response->from())->toBe(15);
    expect($response->perPage())->toBe(15);
});
