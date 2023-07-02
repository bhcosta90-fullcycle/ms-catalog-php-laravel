<?php

namespace App\Repositories\Presenter;

use BRCas\CA\Repository\KeyValueInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KeyValuePresenter implements KeyValueInterface
{
    public function __construct(protected Builder|Model $data, protected $key, protected $value)
    {
        //
    }

    public function items(): array
    {
        return $this->data->pluck($this->value, $this->key)->toArray();
    }
}
