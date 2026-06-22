<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Models\Library\Shelf;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShelfStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_shelf()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->post(route('shelves.store'), [
            'shelf' => ['name' => 'My New Shelf'],
        ]);

        $response->assertJson([
            'success' => true,
            'shelf' => ['name' => 'My New Shelf'],
        ]);
        $this->assertDatabaseHas('shelves', [
            'name' => 'My New Shelf',
            'user_id' => $user->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_shelf()
    {
        $response = $this->post(route('shelves.store'), [
            'shelf' => ['name' => 'Hacked Shelf'],
        ]);

        $response->assertRedirect('/login');
    }

    public function test_store_returns_201_on_creation()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->post(route('shelves.store'), [
            'shelf' => ['name' => 'Status Check'],
        ]);

        $response->assertStatus(201);
    }
}
