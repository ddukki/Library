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

test('unauthenticated user cannot create an extent type', function () {
    $response = $this->post(route('extenttypes.store'), [
        'extentType' => ['name' => 'Hacked'],
    ]);

    $response->assertRedirect('/login');
});
