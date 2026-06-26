<?php

use App\Models\Library\Author;
use App\User;

test('authenticated user can create an author', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $response = $this->post(route('authors.store'), [
        'author' => [
            'first_name' => 'Jane',
            'last_name' => 'Austen',
        ],
    ]);

    $response->assertJson([
        'success' => true,
        'added' => ['first_name' => 'Jane', 'last_name' => 'Austen'],
    ]);
    $this->assertDatabaseHas('authors', [
        'first_name' => 'Jane',
        'last_name' => 'Austen',
    ]);
});

test('authenticated user can update an author', function () {
    $user = User::factory()->create();
    $author = Author::create(['first_name' => 'Old', 'last_name' => 'Name']);

    $this->actingAs($user);
    $response = $this->put(route('authors.update', ['author' => $author->id]), [
        'author' => ['first_name' => 'New', 'last_name' => 'Name'],
    ]);

    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('authors', ['first_name' => 'New']);
});

test('unauthenticated user cannot create an author', function () {
    $response = $this->post(route('authors.store'), [
        'author' => ['first_name' => 'Hacked', 'last_name' => 'Author'],
    ]);

    $response->assertRedirect('/login');
});

test('author creation requires first_name', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $response = $this->post(route('authors.store'), [
        'author' => ['last_name' => 'Only'],
    ]);

    $response->assertSessionHasErrors();
});
