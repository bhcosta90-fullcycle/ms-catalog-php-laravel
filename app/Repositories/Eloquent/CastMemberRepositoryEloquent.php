<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as ModelCastMember;
use App\Repositories\Presenter\ItemPresenter;
use App\Repositories\Presenter\KeyValuePresenter;
use App\Repositories\Presenter\PaginationPresenter;
use BRCas\CA\Domain\Abstracts\EntityAbstract;
use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\KeyValueInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Entity\CastMember;
use BRCas\MV\Domain\Enum\CastMemberType;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CastMemberRepositoryEloquent implements CastMemberRepositoryInterface
{
    public function __construct(protected ModelCastMember $model)
    {
        //
    }

    /**
     * @param  CastMember  $category
     */
    public function insert(EntityAbstract $category): CastMember
    {
        $model = $this->model->create([
            'id' => $category->id(),
            'name' => $category->name,
            'type' => $category->type->value,
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

    public function getById(string $id): CastMember
    {
        $model = $this->findByModel($id);

        return $this->toEntity($model);
    }

    /**
     * @param  CastMember  $category
     */
    public function update(EntityAbstract $category): CastMember
    {
        $model = $this->findByModel($category->id);
        $model->update([
            'name' => $category->name,
            'type' => $category->type->value,
            'is_active' => $category->isActive,
        ]);

        return $this->toEntity($model);
    }

    /**
     * @param  CastMember  $category
     */
    public function delete(EntityAbstract $category): bool
    {
        $model = $this->findByModel($category->id);

        return $model->delete();
    }

    public function getIdsByListId(array $categories = []): KeyValueInterface
    {
        return new KeyValuePresenter($this->model->whereIn('id', $categories), 'id', 'name');
    }

    protected function findByModel(string $id): Model
    {
        if (! $model = ModelCastMember::find($id)) {
            throw new EntityNotFoundException();
        }

        return $model;
    }

    protected function toEntity(Model $model): CastMember
    {
        return new CastMember(
            id: new Uuid($model->id),
            name: $model->name,
            type: CastMemberType::from($model->type),
            isActive: $model->is_active,
            createdAt: $model->created_at,
        );
    }
}
