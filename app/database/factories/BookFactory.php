<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'publisher' => fake()->company(),
            'year' => fake()->year(),
            'isbn' => fake()->isbn13(),
            'category' => fake()->randomElement(['fiction', 'non-fiction', 'history', 'science', 'education']),
            'description' => fake()->paragraph(),
            'stock' => fake()->numberBetween(1, 50),
            'cover_image' => 'default-book.jpg',
        ];
    }
}
