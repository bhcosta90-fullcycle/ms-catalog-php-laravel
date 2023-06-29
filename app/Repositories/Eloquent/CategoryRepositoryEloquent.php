<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as ModelCategory;
use App\Repositories\Presenter\ItemPresenter;
use App\Repositories\Presenter\KeyValuePresenter;
use App\Repositories\Presenter\PaginationPresenter;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\KeyValueInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Entity\Category;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CategoryRepositoryEloquent implements CategoryRepositoryInterface
{
    public function __construct(protected ModelCategory $model)
    {
        //
    }

    public function insert(Category $category): Category
    {
        $model = $this->model->create([
            'id' => $category->id(),
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
            'created_at' => $category->createdAt(),
        ]);

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

    public function getById(string $id): Category
    {
        $model = $this->findByModel($id);
        return $this->toEntity($model);
    }

    public function update(Category $category): Category
    {
        $model = $this->findByModel($category->id);
        $model->update([
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
        ]);

        return $this->toEntity($model);
    }

    public function delete(Category $category): bool
    {
        $model = $this->findByModel($category->id);
        return $model->delete();
    }

    public function getIdsByListId(array $categories = []): KeyValueInterface
    {
        return new KeyValuePresenter($this->model, 'id', 'name');
    }

    protected function findByModel(string $id): Model {
        if (!$model = ModelCategory::find($id)) {
            throw new EntityNotFoundException();
        }

        return $model;
    }

    protected function toEntity(Model $model): Category
    {
        return new Category(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            isActive: $model->is_active,
            createdAt: (string) $model->created_at,
        );
    }
}
