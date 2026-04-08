<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->numerify('08##########'),
            'place' => fake()->city(),
            'book_title' => fake()->sentence(3),
            'event_name' => fake()->randomElement(['Book Reading', 'Author Meet', 'Storytelling']),
            'category' => fake()->randomElement(['book', 'event', 'mobile_library']),
            'visit_time' => fake()->dateTimeBetween('now', '+30 days'),
            'status' => fake()->randomElement(['pending', 'confirmed', 'rejected', 'completed']),
            'audience_category' => fake()->randomElement(['general', 'children', 'students', 'adults']),
            'latitude' => fake()->latitude(-5, -3),
            'longitude' => fake()->longitude(119, 121),
            'request_letter' => null,
        ];
    }
}
