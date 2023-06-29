<?php

use App\Models\Category;

it('returns a successful response', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});
