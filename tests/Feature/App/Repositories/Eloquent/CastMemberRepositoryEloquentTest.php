<?php

use App\Models\CastMember as Model;
use App\Repositories\Eloquent\CastMemberRepositoryEloquent as RepositoryEloquent;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Entity\CastMember as EntityDomain;
use BRCas\MV\Domain\Enum\CastMemberType;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface as RepositoryInterface;

test('validando se o repositório tem o contrato', function () {
    $repository = new RepositoryEloquent(new Model());
    expect($repository)->toBeInstanceOf(RepositoryInterface::class);
});

test('inserindo na base de dados o domínio da aplicação', function () {
    $repository = new RepositoryEloquent(new Model());

    $response = $repository->insert(new EntityDomain(name: 'testing', type: CastMemberType::ACTOR));
    expect($response)->toBeInstanceOf(EntityDomain::class);

    $this->assertDatabaseHas('cast_members', [
        'id' => $response->id(),
        'name' => 'testing',
        'type' => 2,
    ]);
});

test('encontrando o domínio na aplicação', function () {
    $domain = Model::factory()->create();
    $repository = new RepositoryEloquent(new Model());
    $response = $repository->getById($domain->id);

    expect($response)->toBeInstanceOf(EntityDomain::class);
});

test('domínio não encontrado na aplicação', function () {
    $repository = new RepositoryEloquent(new Model());
    $repository->getById('fake-value');
})->throws(EntityNotFoundException::class);

test('listar todos os registros do domínio', function () {
    Model::factory(10)->create();
    $repository = new RepositoryEloquent(new Model());
    $response = $repository->all();
    expect($response)->toBeInstanceOf(ItemInterface::class);
    expect($response->total())->toBe(10);
    expect($response->items())->toHaveCount(10);
});

test('listar todos os registros do domínio quando estiver vazio', function () {
    $repository = new RepositoryEloquent(new Model());
    $response = $repository->all();
    expect($response)->toBeInstanceOf(ItemInterface::class);
    expect($response->total())->toBe(0);
    expect($response->items())->toHaveCount(0);
});

test('listar todos os registros paginados do domínio', function () {
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

test('listar todos os registros paginados do domínio quando estiver vazio', function () {
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

test('editar os registros do domínio', function () {
    $domain = Model::factory()->create();
    $repository = new RepositoryEloquent(new Model());
    $domain = $repository->getById($domain->id);
    $domain->update(name: 'testing');
    $response = $repository->update($domain);

    expect($response)->toBeInstanceOf(EntityDomain::class);

    $this->assertDatabaseHas('cast_members', [
        'id' => $response->id(),
        'name' => 'testing',
    ]);
});

test('editar um domínio que não foi encontrado na aplicação', function () {
    $repository = new RepositoryEloquent(new Model());
    $domain = new EntityDomain(name: 'testing', type: CastMemberType::ACTOR);
    $domain->update(name: 'testing');
    $repository->update($domain);
})->throws(EntityNotFoundException::class);

test('deletar o domínio', function () {
    $domain = Model::factory()->create();
    $repository = new RepositoryEloquent(new Model());
    $domain = $repository->getById($domain->id);
    $response = $repository->delete($domain);

    expect($response)->toBeTrue();

    $this->assertSoftDeleted('cast_members', [
        'id' => $domain->id(),
    ]);
});

test('deletar o domínio que não foi encontrado na aplicação', function () {
    $repository = new RepositoryEloquent(new Model());
    $domain = new EntityDomain(name: 'testing', type: CastMemberType::ACTOR);
    $repository->delete($domain);
})->throws(EntityNotFoundException::class);
