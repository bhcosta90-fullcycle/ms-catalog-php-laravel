<?php

use App\Models\Category;
use App\Models\Genre as Model;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\UseCase\DatabaseTransactionInterface;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface as RepositoryInterface;
use BRCas\MV\UseCases\Genre as UseCase;

test('testando a integração do caso de uso para editar', function () {
    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\UpdateGenreUseCase(
        repository: $repository,
        transaction: app(DatabaseTransactionInterface::class),
        category: app(CategoryRepositoryInterface::class)
    );

    $useCase->execute(new UseCase\DTO\UpdateGenre\Input(
        id: $domain->id,
        name: 'testing',
    ));

    $this->assertDatabaseHas('genres', [
        'id' => $domain->id,
        'name' => 'testing',
    ]);
});

test('testando a integração do caso de uso para editar com categoria', function () {
    $categories = Category::factory(4)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();

    $domain = Model::factory()->create();
    $domain->categories()->sync([$categories[0], $categories[1]]);

    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\UpdateGenreUseCase(
        repository: $repository,
        transaction: app(DatabaseTransactionInterface::class),
        category: app(CategoryRepositoryInterface::class)
    );

    $useCase->execute(new UseCase\DTO\UpdateGenre\Input(
        id: $domain->id,
        name: 'testing',
        categories: [$categories[2], $categories[3]]
    ));

    $this->assertDatabaseHas('genres', [
        'id' => $domain->id,
        'name' => 'testing',
    ]);

    $this->assertDatabaseCount('category_genre', 2);
});

test('testando a integração do caso de uso para editar com categoria inexistente', function () {
    $categories = Category::factory(4)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();

    $domain = Model::factory()->create();
    $domain->categories()->sync([$categories[0], $categories[1]]);

    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\UpdateGenreUseCase(
        repository: $repository,
        transaction: app(DatabaseTransactionInterface::class),
        category: app(CategoryRepositoryInterface::class)
    );

    $useCase->execute(new UseCase\DTO\UpdateGenre\Input(
        id: $domain->id,
        name: 'testing',
        categories: ['10', $categories[2], $categories[3]]
    ));
})->throws(EntityNotFoundException::class, 'Category 10 not found');

test('testando o rollback da transação', function () {
    $categories = Category::factory(4)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();

    $domain = Model::factory()->create();
    $repository = app(RepositoryInterface::class);
    $useCase = new UseCase\UpdateGenreUseCase(
        repository: $repository,
        transaction: app(DatabaseTransactionInterface::class),
        category: app(CategoryRepositoryInterface::class)
    );

    try {
        $useCase->execute(new UseCase\DTO\UpdateGenre\Input(
            id: $domain->id,
            name: 'testing',
            categories: ['10', $categories[2], $categories[3]]
        ));
    } catch (Throwable) {
        $this->assertDatabaseHas('genres', [
            'id' => $domain->id,
            'name' => $domain->name,
        ]);
        $this->assertDatabaseCount('category_genre', 0);
    }
});
