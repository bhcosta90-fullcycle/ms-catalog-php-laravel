<?php

use App\Models\Category as Model;
use App\Models\Traits\UuidTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\Unit\app\Models\Actions\ModelTestCase;

test('if use traits', function () {
    ModelTestCase::make(new Model)->traits([HasFactory::class, SoftDeletes::class, UuidTrait::class]);
});

test('if use fillable', function () {
    ModelTestCase::make(new Model)->fillable(['id', 'name', 'is_active']);
});

test('if use casts', function () {
    ModelTestCase::make(new Model)->casts(['is_active' => 'boolean', 'deleted_at' => 'datetime']);
});
