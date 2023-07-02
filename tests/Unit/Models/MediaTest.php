<?php

use App\Models\Media as Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\Unit\Models\Actions\ModelTestCase;

test('testando se o model tem as traits', function () {
    ModelTestCase::make(new Model)->traits([HasFactory::class, SoftDeletes::class, HasUuids::class]);
});

test('testando se o model tem os fillable', function () {
    ModelTestCase::make(new Model)->fillable([
        'file_path',
        'encoded_path',
        'media_status',
        'type',
    ]);
});

test('testando se o model tem os casts', function () {
    ModelTestCase::make(new Model)->casts([
        'media_status' => 'integer',
        'type' => 'integer',
        'deleted_at' => 'datetime',
    ]);
});

test('testando o nome da tabela', function () {
    expect(with(new Model)->getTable())->toBe('medias_video');
});

test('testando se existe a função para vincular o video', function () {
    expect(method_exists(new Model, 'video'))->toBeTrue();
});
