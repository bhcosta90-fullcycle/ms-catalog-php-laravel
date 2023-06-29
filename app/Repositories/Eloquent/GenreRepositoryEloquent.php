<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre as ModelGenre;
use App\Repositories\Presenter\ItemPresenter;
use App\Repositories\Presenter\PaginationPresenter;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Entity\Genre;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class GenreRepositoryEloquent implements GenreRepositoryInterface
{
    public function __construct(protected ModelGenre $model)
    {
        //
    }

    public function insert(Genre $genre): Genre
    {
        $model = $this->model->create([
            'id' => $genre->id(),
            'name' => $genre->name,
            'is_active' => $genre->isActive,
            'created_at' => $genre->createdAt(),
        ]);

        $model->categories()->sync($genre->categories);

        return $this->toEntity($model);
    }

    public function all(): ItemInterface
    {
        return new ItemPresenter($this->model->get());
    }

    public function paginate(): PaginateInterface
    {
        return new PaginationPresenter($this->model->paginate());
    }

    public function getById(string $id): Genre
    {
        $model = $this->findByModel($id);
        return $this->toEntity($model);
    }

    public function update(Genre $genre): Genre
    {
        $model = $this->findByModel($genre->id);
        $model->update([
            'name' => $genre->name,
            'is_active' => $genre->isActive,
        ]);
        $model->categories()->sync($genre->categories);

        return $this->toEntity($model);
    }

    public function delete(Genre $genre): bool
    {
        $model = $this->findByModel($genre->id);
        return $model->delete();
    }

    protected function findByModel(string $id): Model {
        if (!$model = ModelGenre::find($id)) {
            throw new EntityNotFoundException();
        }

        return $model;
    }

    protected function toEntity(Model $model): Genre
    {
        return new Genre(
            id: new Uuid($model->id),
            name: $model->name,
            isActive: $model->is_active,
            createdAt: $model->created_at,
        );
    }
}
