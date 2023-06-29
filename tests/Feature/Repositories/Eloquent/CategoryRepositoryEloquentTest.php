<?php

use App\Models\Category;
use App\Repositories\Eloquent\CategoryRepositoryEloquent;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Entity\Category as EntityCategory;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;

test("validando se o repositório tem o contrato", function () {
    $repository = new CategoryRepositoryEloquent(new Category());
    expect($repository)->toBeInstanceOf(CategoryRepositoryInterface::class);
});

test("inserindo na base de dados o domínio da aplicação", function () {
    $repository = new CategoryRepositoryEloquent(new Category());

    $response = $repository->insert(new EntityCategory(name: 'testing'));
    expect($response)->toBeInstanceOf(EntityCategory::class);

    $this->assertDatabaseHas('categories', [
        'id' => $response->id(),
        'name' => $response->name,
    ]);
});

test("encontrando o domínio na aplicação", function () {
    $category = Category::factory()->create();
    $repository = new CategoryRepositoryEloquent(new Category());
    $response = $repository->getById($category->id);

    expect($response)->toBeInstanceOf(EntityCategory::class);
});

test("domínio não encontrado na aplicação", function () {
    $repository = new CategoryRepositoryEloquent(new Category());
    $repository->getById('fake-value');
})->throws(EntityNotFoundException::class);

test("listar todos os dados do domínio", function () {
    Category::factory(10)->create();
    $repository = new CategoryRepositoryEloquent(new Category());
    $response = $repository->all();
    expect($response)->toBeInstanceOf(ItemInterface::class);
    expect($response->total())->toBe(10);
    expect($response->items())->toHaveCount(10);
});

test("listar todos os dados do domínio quando estiver vazio", function () {
    $repository = new CategoryRepositoryEloquent(new Category());
    $response = $repository->all();
    expect($response)->toBeInstanceOf(ItemInterface::class);
    expect($response->total())->toBe(0);
    expect($response->items())->toHaveCount(0);
});

test("listar todos os dados paginados do domínio", function () {
    Category::factory(50)->create();
    $repository = new CategoryRepositoryEloquent(new Category());
    $response = $repository->paginate();
    expect($response)->toBeInstanceOf(PaginateInterface::class);
    expect($response->total())->toBe(50);
    expect($response->items())->toHaveCount(15);
    expect($response->currentPage())->toBe(1);
    expect($response->firstPage())->toBe(1);
    expect($response->lastPage())->toBe(4);
    expect($response->to())->toBe(1);
    expect($response->from())->toBe(15);
    expect($response->perPage())->toBe(15);
});

test("listar todos os dados paginados do domínio quando estiver vazio", function () {
    $repository = new CategoryRepositoryEloquent(new Category());
    $response = $repository->paginate();
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

test("editar os dados do domínio", function () {
    $category = Category::factory()->create();
    $repository = new CategoryRepositoryEloquent(new Category());
    $domain = $repository->getById($category->id);
    $domain->update(name: 'testing', description: 'description');
    $response = $repository->update($domain);

    expect($response)->toBeInstanceOf(EntityCategory::class);

    $this->assertDatabaseHas('categories', [
        'id' => $response->id(),
        'name' => 'testing',
        'description' => 'description',
    ]);
});

test("editar um domínio que não foi encontrado na aplicação", function () {
    $repository = new CategoryRepositoryEloquent(new Category());
    $domain = new EntityCategory(name: 'testing');
    $domain->update(name: 'testing', description: 'description');
    $repository->update($domain);
})->throws(EntityNotFoundException::class);

test("deletar o domínio", function () {
    $category = Category::factory()->create();
    $repository = new CategoryRepositoryEloquent(new Category());
    $domain = $repository->getById($category->id);
    $response = $repository->delete($domain);

    expect($response)->toBeTrue();

    $this->assertSoftDeleted('categories', [
        'id' => $domain->id(),
    ]);
});

test("deletar o domínio que não foi encontrado na aplicação", function () {
    $repository = new CategoryRepositoryEloquent(new Category());
    $domain = new EntityCategory(name: 'testing');
    $repository->delete($domain);
})->throws(EntityNotFoundException::class);
