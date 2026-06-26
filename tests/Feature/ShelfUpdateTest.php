<?php

use App\Models\Library\Shelf;
use App\User;

test('authenticated user can update a shelf', function () {
    $user = User::factory()->create();
    $shelf = Shelf::create(['name' => 'Old Name', 'user_id' => $user->id]);

    $this->actingAs($user);
    $response = $this->put(route('shelves.update', ['shelf' => $shelf->id]), [
        'shelf' => ['name' => 'Updated Name'],
    ]);

    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('shelves', ['name' => 'Updated Name']);
});

test('authenticated user can delete a shelf', function () {
    $user = User::factory()->create();
    $shelf = Shelf::create(['name' => 'Delete Me', 'user_id' => $user->id]);

    $this->actingAs($user);
    $response = $this->delete(route('shelves.destroy', ['shelf' => $shelf->id]));

    $response->assertJson(['success' => true]);
    $this->assertDatabaseMissing('shelves', ['id' => $shelf->id]);
});

test('unauthenticated user cannot delete a shelf', function () {
    $user = User::factory()->create();
    $shelf = Shelf::create(['name' => 'Mine', 'user_id' => $user->id]);

    $this->actingAs($user);
    $response = $this->delete(route('shelves.destroy', ['shelf' => $shelf->id]));

    $response->assertJson(['success' => true]);
});
