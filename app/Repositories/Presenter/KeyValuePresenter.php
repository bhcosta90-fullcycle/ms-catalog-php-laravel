<?php

namespace App\Repositories\Presenter;

use BRCas\CA\Repository\KeyValueInterface;
use stdClass;

class KeyValuePresenter implements KeyValueInterface
{
    /**
     * @return stdClass[]
     */
    protected array $items = [];

    public function __construct(protected $data, protected $key, protected $value)
    {
        dd($data);
    }

    public function items(): array
    {
        return $this->items->pluck($this->value, $this->key)->toArray();
    }
}
