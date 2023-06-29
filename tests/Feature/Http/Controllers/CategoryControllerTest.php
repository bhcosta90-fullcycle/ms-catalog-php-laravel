<?php

use App\Models\Category as Model;

test("listando todas as categorias quando está vázia", function () {
    $response = $this->get('/categories');
    $response->assertStatus(200);
    $response->assertJsonCount(0, 'data');
});

test("listando todas as categorias", function () {
    Model::factory(50)->create();
    $response = $this->get('/categories');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'meta' => [
            'total',
            'current_page',
            'last_page',
            'first_page',
            'per_page',
            'to',
            'from',
        ],
    ]);
    $response->assertJsonCount(15, 'data');
});

test("listando a quarta página dos dados", function () {
    Model::factory(50)->create();
    $response = $this->get('/categories?page=4');
    $response->assertStatus(200);
    $this->assertEquals(4, $response['meta']['current_page']);
    $this->assertEquals(50, $response['meta']['total']);
    $response->assertJsonCount(5, 'data');
});

test("listando um dado que não existe em nossa base de dados", function () {
    $response = $this->get('/categories/fake-value');
    $response->assertStatus(404);
});

test("listando um dado na nossa base de dados", function () {
    $category = Model::factory()->create();
    $response = $this->get('/categories/' . $category->id);
    $response->assertStatus(200);
});
