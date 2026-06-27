<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Models\Library\Book;
use App\Models\Library\Edition;
use App\Models\Library\ExtentType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditionStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_edition()
    {
        $user = User::factory()->create();
        $book = Book::create(['title' => 'Test Book']);
        $extentType = ExtentType::create(['name' => 'Page']);

        $this->actingAs($user);
        $response = $this->post(route('editions.store'), [
            'book' => ['id' => $book->id],
            'edition' => [
                'name' => 'First Edition',
                'extent_type_id' => $extentType->id,
                'extent' => 450,
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
            'extent_type_id' => $extentType->id,
            'extent' => 450,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_edition()
    {
        $response = $this->post(route('editions.store'), [
            'book' => ['id' => 1],
                'edition' => ['name' => 'Hacked', 'extent_type_id' => 1, 'extent' => 0],
        ]);

        $response->assertRedirect('/login');
    }
}
