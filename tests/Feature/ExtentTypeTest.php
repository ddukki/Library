<?php

use App\Models\Library\ExtentType;
use App\User;

test('authenticated user can create an extent type', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $response = $this->post(route('extenttypes.store'), [
        'extentType' => ['name' => 'Page'],
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('extent_types', ['name' => 'Page']);
});

test('authenticated user can view all extent types', function () {
    $user = User::factory()->create();
    ExtentType::create(['name' => 'Page']);
    ExtentType::create(['name' => 'Location']);

    $this->actingAs($user);
    $response = $this->get(route('extenttypes.all'));

    $response->assertJsonCount(2, 'extentTypes');
});

test('authenticated user can delete an extent type', function () {
    $user = User::factory()->create();
    $type = ExtentType::create(['name' => 'Delete Me']);

    $this->actingAs($user);
    $response = $this->delete(route('extenttypes.destroy', ['extenttype' => $type->id]));

    $response->assertStatus(204);
    $this->assertDatabaseMissing('extent_types', ['id' => $type->id]);
});

test('authenticated user can update an extent type', function () {
    $user = User::factory()->create();
    $type = ExtentType::create(['name' => 'Old Name']);

    $this->actingAs($user);
    $response = $this->put(route('extenttypes.update', ['extenttype' => $type->id]), [
        'extentType' => ['name' => 'New Name'],
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('updated.name', 'New Name');
    $this->assertDatabaseHas('extent_types', ['id' => $type->id, 'name' => 'New Name']);
});

test('usage endpoint returns zero for unused extent type', function () {
    $user = User::factory()->create();
    $type = ExtentType::create(['name' => 'Unused']);

    $this->actingAs($user);
    $response = $this->get(route('extenttypes.usage', ['extenttype' => $type->id]));

    $response->assertStatus(200);
    $response->assertJson(['editions_count' => 0]);
});

test('usage endpoint returns edition count for used extent type', function () {
    $user = User::factory()->create();
    $book = App\Models\Library\Book::create(['title' => 'Test Book']);
    $type = ExtentType::create(['name' => 'Page']);
    App\Models\Library\Edition::create([
        'book_id' => $book->id,
        'name' => 'First Edition',
        'extent_type_id' => $type->id,
        'extent' => 0,
    ]);
    App\Models\Library\Edition::create([
        'book_id' => $book->id,
        'name' => 'Second Edition',
        'extent_type_id' => $type->id,
        'extent' => 0,
    ]);

    $this->actingAs($user);
    $response = $this->get(route('extenttypes.usage', ['extenttype' => $type->id]));

    $response->assertStatus(200);
    $response->assertJson(['editions_count' => 2]);
});

test('unauthenticated user cannot create an extent type', function () {
    $response = $this->post(route('extenttypes.store'), [
        'extentType' => ['name' => 'Hacked'],
    ]);

    $response->assertRedirect('/login');
});
