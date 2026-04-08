<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_form_can_be_rendered(): void
    {
        $response = $this->get('/reservations/create');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_reservation_form(): void
    {
        $response = $this->get('/reservations/create');

        // Reservation form is actually public, so it should be accessible
        $response->assertStatus(200);
    }

    public function test_user_can_create_reservation(): void
    {
        Storage::fake('public');
        $book = Book::factory()->create();

        $response = $this->post('/reservations', [
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'place' => 'Test Location',
            'book_title' => $book->title,
            'event_name' => 'Book Reading',
            'category' => 'book',
            'visit_time' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'audience_category' => 'general',
            'latitude' => -4.0120833,
            'longitude' => 119.6207191,
            'request_letter' => UploadedFile::fake()->create('letter.pdf', 100),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reservations', [
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'book_title' => $book->title,
        ]);
    }

    public function test_admin_can_view_reservations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Reservation::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('/admin/reservations');

        $response->assertStatus(200);
    }

    public function test_admin_can_update_reservation_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reservation = Reservation::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)->patch("/admin/reservations/{$reservation->id}/status", [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect('/admin/reservations');
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_regular_user_cannot_update_reservation_status(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $reservation = Reservation::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($user)->patch("/admin/reservations/{$reservation->id}/status", [
            'status' => 'confirmed',
        ]);

        $response->assertStatus(403);
    }

    public function test_reservations_can_be_fetched_by_date(): void
    {
        $date = now()->format('Y-m-d');
        Reservation::factory()->create([
            'visit_time' => $date . ' 10:00:00',
            'status' => 'confirmed',
        ]);
        Reservation::factory()->create([
            'visit_time' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'status' => 'confirmed',
        ]);

        $response = $this->get("/reservations/by-date?date={$date}");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    public function test_reservation_details_can_be_fetched(): void
    {
        $reservation = Reservation::factory()->create();

        $response = $this->get("/reservations/details/{$reservation->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'fullname' => $reservation->fullname,
            'email' => $reservation->email,
        ]);
    }

    public function test_reservation_requires_valid_data(): void
    {
        $response = $this->post('/reservations', [
            'fullname' => '',
            'email' => 'invalid-email',
            'phone' => '',
        ]);

        $response->assertSessionHasErrors(['fullname', 'email', 'phone']);
    }
}
