<?php

namespace Tests\Unit\app\Models\Actions;

use Illuminate\Database\Eloquent\Model;

class ModelTestCase
{
    protected function __construct(protected Model $model)
    {
        //
    }

    public static function make(Model $model)
    {
        return new ModelTestCase($model);
    }

    public function fillable(array $fillable)
    {
        expect($fillable)->toBe($this->model->getFillable());
        return $this;
    }

    public function traits(array $traits)
    {
        expect($traits)->toBe(array_values(class_uses($this->model)));
        return $this;
    }

    public function casts(array $casts)
    {
        expect($casts)->toBe($this->model->getCasts());
        return $this;
    }
}
