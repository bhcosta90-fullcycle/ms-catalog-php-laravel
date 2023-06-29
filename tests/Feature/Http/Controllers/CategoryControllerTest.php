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

test("listando a quarta página dos registros", function () {
    Model::factory(50)->create();
    $response = $this->get('/categories?page=4');
    $response->assertStatus(200);
    $this->assertEquals(4, $response['meta']['current_page']);
    $this->assertEquals(50, $response['meta']['total']);
    $response->assertJsonCount(5, 'data');
});

test("listando um registro que não existe em nossa base de dados", function () {
    $response = $this->get('/categories/fake-value');
    $response->assertStatus(404);
});

test("listando um registro na nossa base de dados", function () {
    $category = Model::factory()->create();
    $response = $this->get('/categories/' . $category->id);
    $response->assertStatus(200);
});

test("cadastrando um novo registro em nossa base de dados", function () {
    $response = $this->postJson('/categories', [
        'name' => 'testing',
        'description' => 'testing',
        'is_active' => false,
    ]);
    $response->assertStatus(201);

    $this->assertEquals('testing', $response['data']['name']);
    $this->assertEquals('testing', $response['data']['description']);
    $this->assertEquals(false, $response['data']['is_active']);
    $this->assertDatabaseHas('categories', [
        'id' => $response['data']['id'],
        'name' => 'testing',
        'description' => 'testing',
        'is_active' => false,
    ]);
});

test("atualizando um registro que não foi encontrado", function () {
    $response = $this->putJson('/categories/fake-id', [
        'name' => 'testing',
        'description' => 'testing',
        'is_active' => false,
    ]);
    $response->assertStatus(404);
});

test("atualizando um registro", function () {
    $category = Model::factory()->create();

    $response = $this->putJson('/categories/' . $category->id, [
        'name' => 'testing',
        'description' => 'testing',
        'is_active' => false,
    ]);
    $response->assertStatus(200);

    $this->assertEquals('testing', $response['data']['name']);
    $this->assertEquals('testing', $response['data']['description']);
    $this->assertEquals(false, $response['data']['is_active']);
    $this->assertDatabaseHas('categories', [
        'id' => $response['data']['id'],
        'name' => 'testing',
        'description' => 'testing',
        'is_active' => false,
    ]);
});

test("deletando um registro que não foi encontrado", function () {
    $response = $this->deleteJson('/categories/fake-id');
    $response->assertStatus(404);
});

test("deletando um registro", function () {
    $category = Model::factory()->create();
    $response = $this->deleteJson('/categories/' . $category->id);
    $response->assertStatus(204);

    $this->assertSoftDeleted('categories', [
        'id' => $category->id,
    ]);
});
