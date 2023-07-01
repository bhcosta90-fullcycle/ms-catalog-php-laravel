<?php

use App\Models\CastMember;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video as Model;
use App\Repositories\Eloquent\VideoRepositoryEloquent as RepositoryEloquent;
use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface as RepositoryInterface;
use BRCas\MV\Domain\Entity\Video as EntityDomain;
use BRCas\MV\Domain\Enum\MediaStatus;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\ValueObject\Image;
use BRCas\MV\Domain\ValueObject\Media;

test("validando se o repositório tem o contrato", function () {
    $repository = new RepositoryEloquent(new Model());
    expect($repository)->toBeInstanceOf(RepositoryInterface::class);
});

test("inserindo na base de dados o domínio da aplicação", function () {
    $repository = new RepositoryEloquent(new Model());

    $response = $repository->insert(new EntityDomain(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L
    ));
    expect($response)->toBeInstanceOf(EntityDomain::class);

    $this->assertDatabaseHas('videos', [
        'id' => $response->id(),
        'title' => 'testing',
        'description' => 'testing',
        'year_launched' => 2010,
        'duration' => 50,
        'opened' => true,
        'rating' => 'L',
    ]);
});

test("inserindo na base de dados e integrando com os relacionamentos", function () {
    $categories = Category::factory(2)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    $genres = Genre::factory(3)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    $castMembers = CastMember::factory(4)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();

    $repository = new RepositoryEloquent(new Model());

    $response = $repository->insert(new EntityDomain(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        categories: $categories,
        genres: $genres,
        castMembers: $castMembers,
    ));

    expect($response->categories)->toHaveCount(2);
    expect($response->genres)->toHaveCount(3);
    expect($response->castMembers)->toHaveCount(4);

    expect($categories)->toBe($response->categories);
    expect($genres)->toBe($response->genres);
    expect($castMembers)->toBe($response->castMembers);
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

test("listar todos os registros do domínio", function () {
    Model::factory(10)->create();
    $repository = new RepositoryEloquent(new Model());
    $response = $repository->all();
    expect($response)->toBeInstanceOf(ItemInterface::class);
    expect($response->total())->toBe(10);
    expect($response->items())->toHaveCount(10);
});

test("listar todos os registros do domínio quando estiver vazio", function () {
    $repository = new RepositoryEloquent(new Model());
    $response = $repository->all();
    expect($response)->toBeInstanceOf(ItemInterface::class);
    expect($response->total())->toBe(0);
    expect($response->items())->toHaveCount(0);
});

test("listar todos os registros paginados do domínio", function () {
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

test("listar todos os registros paginados do domínio quando estiver vazio", function () {
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

test("deletar o domínio", function () {
    $domain = Model::factory()->create();
    $repository = new RepositoryEloquent(new Model());
    $domain = $repository->getById($domain->id);
    $response = $repository->delete($domain);

    expect($response)->toBeTrue();

    $this->assertSoftDeleted('videos', [
        'id' => $domain->id(),
    ]);
});

test("deletar o domínio que não foi encontrado na aplicação", function () {
    $repository = new RepositoryEloquent(new Model());
    $domain = new EntityDomain(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L
    );
    $repository->delete($domain);
})->throws(EntityNotFoundException::class);

test("editar os registros do domínio", function () {
    $domain = Model::factory()->create();
    $repository = new RepositoryEloquent(new Model());
    $domain = $repository->getById($domain->id);
    $domain->update(title: 'testing', description: 'testing');
    $response = $repository->update($domain);

    expect($response)->toBeInstanceOf(EntityDomain::class);

    $this->assertDatabaseHas('videos', [
        'id' => $response->id(),
        'title' => 'testing',
        'description' => 'testing',
    ]);
});

test("editar os registros do domínio com os relacionamentos", function () {

    $categories = Category::factory(2)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    $genres = Genre::factory(3)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();
    $castMembers = CastMember::factory(4)->create()->pluck('id')->map(fn ($rs) => (string) $rs)->toArray();

    $domain = Model::factory()->create();
    $repository = new RepositoryEloquent(new Model());
    $domain = $repository->getById($domain->id);
    $domain->update(title: 'testing', description: 'testing');
    array_map(fn($rs) => $domain->addCategory($rs), $categories);
    array_map(fn($rs) => $domain->addGenre($rs), $genres);
    array_map(fn($rs) => $domain->addCastMember($rs), $castMembers);

    $response = $repository->update($domain);

    expect($response)->toBeInstanceOf(EntityDomain::class);

    $this->assertDatabaseHas('videos', [
        'id' => $response->id(),
        'title' => 'testing',
        'description' => 'testing',
    ]);

    expect($response->categories)->toHaveCount(2);
    expect($response->genres)->toHaveCount(3);
    expect($response->castMembers)->toHaveCount(4);

    expect($categories)->toBe($response->categories);
    expect($genres)->toBe($response->genres);
    expect($castMembers)->toBe($response->castMembers);
});

test("editar um domínio que não foi encontrado na aplicação", function () {
    $repository = new RepositoryEloquent(new Model());
    $domain = new EntityDomain(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L
    );
    $domain->update(title: 'testing', description: 'testing');
    $repository->update($domain);
})->throws(EntityNotFoundException::class);

test("inserindo um registro com o trailer", function () {
    $repository = new RepositoryEloquent(new Model());

    $domain = new EntityDomain(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        trailerFile: new Media(path: 'testing.mp4', status: MediaStatus::PENDING),
    );

    $response = $repository->insert($domain);

    expect($response->trailerFile->path)->toBe('testing.mp4');
    expect($response->trailerFile->status->value)->toBe(2);
    expect($response->trailerFile->encoded)->toBeNull();
});

test("inserindo um registro com o video", function () {
    $repository = new RepositoryEloquent(new Model());

    $domain = new EntityDomain(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        videoFile: new Media(path: 'testing.mp4', status: MediaStatus::PENDING),
    );

    $response = $repository->insert($domain);

    expect($response->videoFile->path)->toBe('testing.mp4');
    expect($response->videoFile->status->value)->toBe(2);
    expect($response->videoFile->encoded)->toBeNull();
});

test("inserindo um registro com o banner", function () {
    $repository = new RepositoryEloquent(new Model());

    $domain = new EntityDomain(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        bannerFile: new Image(image: 'testing.jpg'),
    );

    $response = $repository->insert($domain);

    expect($response->bannerFile->path())->toBe('testing.jpg');
});

test("inserindo um registro com o thumb", function () {
    $repository = new RepositoryEloquent(new Model());

    $domain = new EntityDomain(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        thumbFile: new Image(image: 'testing.jpg'),
    );

    $response = $repository->insert($domain);

    expect($response->thumbFile->path())->toBe('testing.jpg');

});

test("inserindo um registro com o half", function () {
    $repository = new RepositoryEloquent(new Model());

    $domain = new EntityDomain(
        title: 'testing',
        description: 'testing',
        yearLaunched: 2010,
        duration: 50,
        opened: true,
        rating: Rating::L,
        thumbHalf: new Image(image: 'testing.jpg'),
    );

    $response = $repository->insert($domain);

    expect($response->thumbHalf->path())->toBe('testing.jpg');
});
