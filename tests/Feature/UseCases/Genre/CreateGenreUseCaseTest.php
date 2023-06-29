<?php

use App\Models\Category;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\UseCase\DatabaseTransactionInterface;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\Genre as UseCase;

test("testando a integração do caso de uso para a criação", function () {
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\CreateGenreUseCase(
        repository: $repository,
        transaction: app(DatabaseTransactionInterface::class),
        category: app(CategoryRepositoryInterface::class)
    );
    $response = $useCase->execute(new UseCase\DTO\CreateGenre\Input(
        name: 'testing category',
    ));

    $this->assertDatabaseHas('genres', [
        'id' => $response->id,
        'name' => 'testing category',
    ]);
});

test("testando a integração do caso de uso para a criação com as categories", function () {
    $categories = Category::factory(4)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();

    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\CreateGenreUseCase(
        repository: $repository,
        transaction: app(DatabaseTransactionInterface::class),
        category: app(CategoryRepositoryInterface::class)
    );
    $response = $useCase->execute(new UseCase\DTO\CreateGenre\Input(
        name: 'testing genre',
        categories: $categories
    ));

    $this->assertDatabaseHas('genres', [
        'id' => $response->id,
        'name' => 'testing genre',
    ]);

    $this->assertDatabaseCount('category_genre', 4);
});

test("testando a integração do caso de uso para a criação com categoria inexistente", function () {
    $categories = Category::factory(4)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\CreateGenreUseCase(
        repository: $repository,
        transaction: app(DatabaseTransactionInterface::class),
        category: app(CategoryRepositoryInterface::class)
    );
    $useCase->execute(new UseCase\DTO\CreateGenre\Input(
        name: 'testing genre',
        categories: array_merge($categories, ['10'])
    ));
})->throws(EntityNotFoundException::class, 'Category 10 not found');

test("testando o rollback da transação", function () {
    $categories = Category::factory(4)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\CreateGenreUseCase(
        repository: $repository,
        transaction: app(DatabaseTransactionInterface::class),
        category: app(CategoryRepositoryInterface::class)
    );
    try {
        $useCase->execute(new UseCase\DTO\CreateGenre\Input(
            name: 'testing genre',
            categories: array_merge($categories, ['10'])
        ));
    }catch(Throwable) {
        $this->assertDatabaseCount('genres', 0);
        $this->assertDatabaseCount('category_genre', 0);
    }
});
