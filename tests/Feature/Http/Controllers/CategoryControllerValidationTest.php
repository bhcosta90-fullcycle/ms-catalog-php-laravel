<?php

use App\Models\Category as Model;

$endpoint = "/categories";

test("validando nome da categoria na criação", function () use ($endpoint) {
    $response = $this->postJson($endpoint, []);
    $response->assertStatus(422)->assertJsonStructure([
        'message',
        'errors' => [
            'name',
        ]
    ]);
});


test("validando nome da categoria na edição", function () use ($endpoint) {
    $response = $this->putJson($endpoint . '/fake-id', []);
    $response->assertStatus(422)->assertJsonStructure([
        'message',
        'errors' => [
            'name',
        ]
    ]);
});
