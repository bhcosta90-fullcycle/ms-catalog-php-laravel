<?php

use App\Models\Genre as Model;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\Unit\Models\Actions\ModelTestCase;

test('testando se o model tem as traits', function () {
    ModelTestCase::make(new Model)->traits([HasFactory::class, SoftDeletes::class, UuidTrait::class]);
});

test('testando se o model tem os fillable', function () {
    ModelTestCase::make(new Model)->fillable(['id', 'name', 'is_active']);
});

test('testando se o model tem os casts', function () {
    ModelTestCase::make(new Model)->casts(['is_active' => 'boolean', 'deleted_at' => 'datetime']);
});

test('testando se existe a função para vincular o video', function () {
    expect(method_exists(new Model, 'categories'))->toBeTrue();
});
