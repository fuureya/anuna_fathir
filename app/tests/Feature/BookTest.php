<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_books_index_page_can_be_rendered(): void
    {
        $response = $this->get('/books');

        $response->assertStatus(200);
    }

    public function test_book_detail_page_can_be_rendered(): void
    {
        $book = Book::factory()->create();

        $response = $this->get("/books/{$book->id}");

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_books_can_be_searched(): void
    {
        Book::factory()->create(['title' => 'Harry Potter']);
        Book::factory()->create(['title' => 'Lord of the Rings']);

        $response = $this->get('/books?search=Harry');

        $response->assertStatus(200);
        $response->assertSee('Harry Potter');
        $response->assertDontSee('Lord of the Rings');
    }

    public function test_admin_can_view_books_management_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/books');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_book(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/books', [
            'title' => 'New Book',
            'author' => 'Test Author',
            'publisher' => 'Test Publisher',
            'year' => 2024,
            'isbn' => '1234567890123',
            'category' => 'fiction',
            'description' => 'Test description',
            'stock' => 10,
            'cover_image' => UploadedFile::fake()->image('book.jpg'),
        ]);

        $response->assertRedirect('/admin/books');
        $this->assertDatabaseHas('books', [
            'title' => 'New Book',
            'author' => 'Test Author',
        ]);
    }

    public function test_admin_can_update_book(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create(['title' => 'Old Title']);

        $response = $this->actingAs($admin)->put("/admin/books/{$book->id}", [
            'title' => 'Updated Title',
            'author' => $book->author,
            'publisher' => $book->publisher,
            'year' => $book->year,
            'isbn' => $book->isbn,
            'category' => $book->category,
            'description' => $book->description,
            'stock' => $book->stock,
        ]);

        $response->assertRedirect('/admin/books');
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_admin_can_delete_book(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/books/{$book->id}");

        $response->assertRedirect('/admin/books');
        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    public function test_regular_user_cannot_create_book(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post('/admin/books', [
            'title' => 'New Book',
            'author' => 'Test Author',
            'publisher' => 'Test Publisher',
            'year' => 2024,
            'isbn' => '1234567890123',
            'category' => 'fiction',
            'description' => 'Test description',
            'stock' => 10,
        ]);

        $response->assertStatus(403);
    }
}
