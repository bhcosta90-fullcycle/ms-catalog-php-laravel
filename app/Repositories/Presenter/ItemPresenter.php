<?php

namespace App\Repositories\Presenter;

use BRCas\CA\Repository\ItemInterface;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

class ItemPresenter implements ItemInterface
{
    /**
     * @return stdClass[]
     */
    protected array $items = [];

    public function __construct(Collection $data)
    {
        $this->items = $this->resolveItems(
            items: $data
        );
    }

    /** @return stdClass[] */
    public function items(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        return count($this->items());
    }

    private function resolveItems($items): array
    {
        $response = [];

        foreach ($items as $item) {
            $stdClass = new stdClass;
            foreach ($item->toArray() as $key => $value) {
                $stdClass->{$key} = $value;
            }

            array_push($response, $stdClass);
        }

        return $response;
    }
}
