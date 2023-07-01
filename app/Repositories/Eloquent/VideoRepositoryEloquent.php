<?php

namespace App\Repositories\Eloquent;

use BRCas\CA\Domain\Abstracts\EntityAbstract;
use BRCas\CA\Repository\ItemInterface;
use BRCas\CA\Repository\PaginateInterface;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;

class VideoRepositoryEloquent implements VideoRepositoryInterface
{
    /**
     * @param Video $entity
     */
    public function insert(EntityAbstract $entity): EntityAbstract
    {
        throw new \Exception('method ' . __FUNCTION__ . ' do not implemented');
    }

    public function all(): ItemInterface
    {
        throw new \Exception('method ' . __FUNCTION__ . ' do not implemented');
    }

    public function paginate(): PaginateInterface
    {
        throw new \Exception('method ' . __FUNCTION__ . ' do not implemented');
    }

    public function getById(string $id): EntityAbstract
    {
        throw new \Exception('method ' . __FUNCTION__ . ' do not implemented');
    }

    /**
     * @param Video $entity
     */
    public function update(EntityAbstract $entity): EntityAbstract
    {
        throw new \Exception('method ' . __FUNCTION__ . ' do not implemented');
    }

    /**
     * @param Video $entity
     */
    public function delete(EntityAbstract $entity): bool
    {
        throw new \Exception('method ' . __FUNCTION__ . ' do not implemented');
    }
}
