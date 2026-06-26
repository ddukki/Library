<?php

use App\Models\Library\Author;
use App\Models\Library\Book;
use App\User;

test('authenticated user can create a book', function () {
    $user = User::factory()->create();
    $author = Author::create(['first_name' => 'Test', 'last_name' => 'Author']);

    $this->actingAs($user);
    $response = $this->post(route('books.store'), [
        'book' => [
            'title' => 'New Book',
            'authors' => [['id' => $author->id]],
        ],
    ]);

    $response->assertJson([
        'success' => true,
        'added' => ['title' => 'New Book'],
    ]);
    $this->assertDatabaseHas('books', ['title' => 'New Book']);
    $this->assertDatabaseHas('book_authors', ['author_id' => $author->id]);
});

test('authenticated user can update a book', function () {
    $user = User::factory()->create();
    $author = Author::create(['first_name' => 'Test', 'last_name' => 'Author']);
    $book = Book::create(['title' => 'Original']);

    $this->actingAs($user);
    $response = $this->put(route('books.update', ['book' => $book->id]), [
        'book' => [
            'title' => 'Updated',
            'authors' => [['id' => $author->id]],
        ],
    ]);

    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('books', ['title' => 'Updated']);
});

test('unauthenticated user cannot create a book', function () {
    $response = $this->post(route('books.store'), [
        'book' => ['title' => 'Hacked'],
    ]);

    $response->assertRedirect('/login');
});

test('unauthenticated user cannot update a book', function () {
    $book = Book::create(['title' => 'Original']);

    $response = $this->put(route('books.update', ['book' => $book->id]), [
        'book' => ['title' => 'Hacked'],
    ]);

    $response->assertRedirect('/login');
});
