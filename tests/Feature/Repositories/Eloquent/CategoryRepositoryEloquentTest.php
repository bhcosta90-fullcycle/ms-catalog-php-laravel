<?php

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryRepositoryEloquent as RepositoryEloquent;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Entity\Category as EntityDomain;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface as RepositoryInterface;

test("validando se o repositório tem o contrato", function () {
    $repository = new RepositoryEloquent(new Model());
    expect($repository)->toBeInstanceOf(RepositoryInterface::class);
});

test("inserindo na base de dados o domínio da aplicação", function () {
    $repository = new RepositoryEloquent(new Model());

    $response = $repository->insert(new EntityDomain(name: 'testing'));
    expect($response)->toBeInstanceOf(EntityDomain::class);

    $this->assertDatabaseHas('categories', [
        'id' => $response->id(),
        'name' => 'testing',
    ]);
});

test("encontrando o domínio na aplicação", function () {
    $domain = Model::factory()->create();
    $repository = new RepositoryEloquent(new Model());
    $response = $repository->getById($domain->id);

    expect($response)->toBeInstanceOf(EntityDomain::class);
});

test("domínio não encontrado na aplicação", function () {
    $repository = new RepositoryEloquent(new Model());
    $repository->getById('fake-value');
})->throws(EntityNotFoundException::class);

test("listar todos os dados do domínio", function () {
    Model::factory(10)->create();
    $repository = new RepositoryEloquent(new Model());
    $response = $repository->all();
    expect($response)->toBeInstanceOf(ItemInterface::class);
    expect($response->total())->toBe(10);
    expect($response->items())->toHaveCount(10);
});

test("listar todos os dados do domínio quando estiver vazio", function () {
    $repository = new RepositoryEloquent(new Model());
    $response = $repository->all();
    expect($response)->toBeInstanceOf(ItemInterface::class);
    expect($response->total())->toBe(0);
    expect($response->items())->toHaveCount(0);
});

test("listar todos os dados paginados do domínio", function () {
    Model::factory(20)->create();
    $repository = new RepositoryEloquent(new Model());
    $response = $repository->paginate();
    expect($response)->toBeInstanceOf(PaginateInterface::class);
    expect($response->total())->toBe(20);
    expect($response->items())->toHaveCount(15);
    expect($response->currentPage())->toBe(1);
    expect($response->firstPage())->toBe(1);
    expect($response->lastPage())->toBe(2);
    expect($response->to())->toBe(1);
    expect($response->from())->toBe(15);
    expect($response->perPage())->toBe(15);
});

test("listar todos os dados paginados do domínio quando estiver vazio", function () {
    $repository = new RepositoryEloquent(new Model());
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
    $domain = Model::factory()->create();
    $repository = new RepositoryEloquent(new Model());
    $domain = $repository->getById($domain->id);
    $domain->update(name: 'testing', description: 'description');
    $response = $repository->update($domain);

    expect($response)->toBeInstanceOf(EntityDomain::class);

    $this->assertDatabaseHas('categories', [
        'id' => $response->id(),
        'name' => 'testing',
        'description' => 'description',
    ]);
});

test("editar um domínio que não foi encontrado na aplicação", function () {
    $repository = new RepositoryEloquent(new Model());
    $domain = new EntityDomain(name: 'testing');
    $domain->update(name: 'testing', description: 'description');
    $repository->update($domain);
})->throws(EntityNotFoundException::class);

test("deletar o domínio", function () {
    $domain = Model::factory()->create();
    $repository = new RepositoryEloquent(new Model());
    $domain = $repository->getById($domain->id);
    $response = $repository->delete($domain);

    expect($response)->toBeTrue();

    $this->assertSoftDeleted('categories', [
        'id' => $domain->id(),
    ]);
});

test("deletar o domínio que não foi encontrado na aplicação", function () {
    $repository = new RepositoryEloquent(new Model());
    $domain = new EntityDomain(name: 'testing');
    $repository->delete($domain);
})->throws(EntityNotFoundException::class);
