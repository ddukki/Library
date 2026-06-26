<?php

test('guest is redirected from home to login', function () {
    $response = $this->get(route('home'));

    $response->assertRedirect('/login');
});

test('authenticated user can view home', function () {
    $user = App\User::factory()->create();

    $this->actingAs($user);
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Browse Books');
    $response->assertSee('Browse Authors');
});
