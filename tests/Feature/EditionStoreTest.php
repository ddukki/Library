<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Models\Library\Book;
use App\Models\Library\Edition;
use App\Models\Library\LocationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditionStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_edition()
    {
        $user = User::factory()->create();
        $book = Book::create(['title' => 'Test Book']);
        $locationType = LocationType::create(['name' => 'Hardcover']);

        $this->actingAs($user);
        $response = $this->post(route('editions.store'), [
            'book' => ['id' => $book->id],
            'edition' => [
                'name' => 'First Edition',
                'location_type_id' => $locationType->id,
                'location_size' => 450,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'added' => ['name' => 'First Edition'],
        ]);
        $this->assertDatabaseHas('editions', [
            'book_id' => $book->id,
            'name' => 'First Edition',
            'location_type_id' => $locationType->id,
            'location_size' => 450,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_edition()
    {
        $response = $this->post(route('editions.store'), [
            'book' => ['id' => 1],
            'edition' => ['name' => 'Hacked', 'location_type_id' => 1, 'location_size' => 0],
        ]);

        $response->assertRedirect('/login');
    }
}
