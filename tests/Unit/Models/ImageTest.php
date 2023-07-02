<?php

use App\Models\Image as Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\Unit\Models\Actions\ModelTestCase;

test('testando se o model tem as traits', function () {
    ModelTestCase::make(new Model)->traits([HasFactory::class, HasUuids::class, SoftDeletes::class]);
});

test('testando se o model tem os fillable', function () {
    ModelTestCase::make(new Model)->fillable([
        'path',
        'type',
    ]);
});

test('testando se o model tem os casts', function () {
    ModelTestCase::make(new Model)->casts([
        'type' => 'integer',
        'deleted_at' => 'datetime',
    ]);
});

test('testando o nome da tabela', function () {
    expect(with(new Model)->getTable())->toBe('images_video');
});

test('testando se existe a função para vincular o video', function () {
    expect(method_exists(new Model, 'video'))->toBeTrue();
});
