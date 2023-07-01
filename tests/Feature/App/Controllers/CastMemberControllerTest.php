<?php

use App\Models\CastMember as Model;
beforeEach(fn() => $this->endpoint = "/cast-members");

test("listando todas do elenco quando está vázia", function () {
    $response = $this->get($this->endpoint);
    $response->assertStatus(200);
    $response->assertJsonCount(0, 'data');
});

test("listando todas do elenco", function () {
    Model::factory(50)->create();
    $response = $this->get($this->endpoint);
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
    $response = $this->get($this->endpoint . '?page=4');
    $response->assertStatus(200);
    $this->assertEquals(4, $response['meta']['current_page']);
    $this->assertEquals(50, $response['meta']['total']);
    $response->assertJsonCount(5, 'data');
});

test("listando um registro que não existe em nossa base de dados", function () {
    $response = $this->get($this->endpoint . '/fake-value');
    $response->assertStatus(404);
});

test("listando um registro na nossa base de dados", function () {
    $model = Model::factory()->create();
    $response = $this->get($this->endpoint . '/' . $model->id);
    $response->assertStatus(200);
});

test("cadastrando um novo registro em nossa base de dados", function () {
    $response = $this->postJson($this->endpoint, [
        'name' => 'testing',
        'type' => 1,
    ]);
    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'name',
                'type',
                'is_active',
                'created_at',
            ],
        ]);

    $this->assertEquals('testing', $response['data']['name']);
    $this->assertEquals(1, $response['data']['type']);
    $this->assertEquals(true, $response['data']['is_active']);
    $this->assertDatabaseHas('cast_members', [
        'id' => $response['data']['id'],
        'name' => 'testing',
        'type' => 1,
        'is_active' => true,
    ]);

    $response = $this->postJson($this->endpoint, [
        'name' => 'testing',
        'type' => 2,
        'is_active' => false,
    ]);
    $this->assertEquals('testing', $response['data']['name']);
    $this->assertEquals(2, $response['data']['type']);
    $this->assertEquals(false, $response['data']['is_active']);
    $this->assertDatabaseHas('cast_members', [
        'id' => $response['data']['id'],
        'name' => 'testing',
        'type' => 2,
        'is_active' => false,
    ]);
});

test("atualizando um registro que não foi encontrado", function () {
    $response = $this->putJson($this->endpoint . '/fake-id', [
        'name' => 'testing',
        'is_active' => false,
    ]);
    $response->assertStatus(404);
});

test("atualizando um registro", function () {
    $model = Model::factory(['type' => 1])->create();

    $response = $this->putJson($this->endpoint . '/' . $model->id, [
        'name' => 'testing',
        'is_active' => false,
    ]);
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'name',
                'type',
                'is_active',
                'created_at',
            ],
        ]);

    $this->assertEquals('testing', $response['data']['name']);
    $this->assertEquals(1, $response['data']['type']);
    $this->assertEquals(false, $response['data']['is_active']);
    $this->assertDatabaseHas('cast_members', [
        'id' => $response['data']['id'],
        'name' => 'testing',
        'type' => 1,
        'is_active' => false,
    ]);
});

test("deletando um registro que não foi encontrado", function () {
    $response = $this->deleteJson($this->endpoint . '/fake-id');
    $response->assertStatus(404);
});

test("deletando um registro", function () {
    $model = Model::factory()->create();
    $response = $this->deleteJson($this->endpoint . '/' . $model->id);
    $response->assertStatus(204);

    $this->assertSoftDeleted('cast_members', [
        'id' => $model->id,
    ]);
});
