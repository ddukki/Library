<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Models\Library\Shelf;
use App\Models\Library\Book;
use App\Models\Library\Edition;
use App\Models\Library\ExtentType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditionShelveTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_shelve_an_edition()
    {
        $user = User::factory()->create();
        $shelf = Shelf::create([
            'name' => 'Test Shelf',
            'user_id' => $user->id,
        ]);
        $book = Book::create(['title' => 'Test Book']);
        $extentType = ExtentType::create(['name' => 'Page']);
        $edition = Edition::create([
            'book_id' => $book->id,
            'name' => 'Test Edition',
            'extent_type_id' => $extentType->id,
            'extent' => 0,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('editions.shelve', [
            'edition' => $edition->id,
            'shelf' => $shelf->id,
        ]));

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('edition_shelves', [
            'edition_id' => $edition->id,
            'shelf_id' => $shelf->id,
        ]);
    }
}
