<?php

use App\Models\Library\LocationType;
use App\User;

test('authenticated user can create a location type', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $response = $this->post(route('locationtypes.store'), [
        'locationType' => ['name' => 'Hardcover'],
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('location_types', ['name' => 'Hardcover']);
});

test('authenticated user can view all location types', function () {
    $user = User::factory()->create();
    LocationType::create(['name' => 'Paperback']);
    LocationType::create(['name' => 'Hardcover']);

    $this->actingAs($user);
    $response = $this->get(route('locationtypes.all'));

    $response->assertJsonCount(2, 'locationTypes');
});

test('authenticated user can delete a location type', function () {
    $user = User::factory()->create();
    $type = LocationType::create(['name' => 'Delete Me']);

    $this->actingAs($user);
    $response = $this->delete(route('locationtypes.destroy', ['locationtype' => $type->id]));

    $response->assertStatus(204);
    $this->assertDatabaseMissing('location_types', ['id' => $type->id]);
});

test('unauthenticated user cannot create a location type', function () {
    $response = $this->post(route('locationtypes.store'), [
        'locationType' => ['name' => 'Hacked'],
    ]);

    $response->assertRedirect('/login');
});
