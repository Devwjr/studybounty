<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BountyFactory extends Factory
{
    public function definition(): array
    {
        $subjects = ['Matemática', 'Programação', 'Física', 'Química', 'História', 'Biologia', 'Português', 'Inglês'];

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraphs(2, true),
            'subject' => fake()->randomElement($subjects),
            'price' => fake()->randomFloat(2, 5, 200),
            'deadline' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'status' => 'OPEN',
            'attachment_url' => fake()->optional()->url(),
            'user_id' => User::factory(),
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'IN_PROGRESS',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'COMPLETED',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'CANCELLED',
        ]);
    }
}
