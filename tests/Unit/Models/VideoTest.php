<?php

use App\Models\Video as Model;
use App\Models\Traits\UuidTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\Unit\Models\Actions\ModelTestCase;

test('testando se o model tem as traits', function () {
    ModelTestCase::make(new Model)->traits([HasFactory::class, SoftDeletes::class, UuidTrait::class]);
});

test('testando se o model tem os fillable', function () {
    ModelTestCase::make(new Model)->fillable([
        'id',
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duration',
    ]);
});

test('testando se o model tem os casts', function () {
    ModelTestCase::make(new Model)->casts([
        'is_active' => 'boolean',
        'year_launched' => 'integer',
        'opened' => 'boolean',
        'duration' => 'integer',
        'deleted_at' => 'datetime',
    ]);
});
