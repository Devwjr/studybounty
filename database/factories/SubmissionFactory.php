<?php

namespace Database\Factories;

use App\Models\Bounty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'content' => fake()->paragraphs(3, true),
            'attachment_url' => fake()->optional()->url(),
            'status' => 'PENDING',
            'price' => fake()->randomFloat(2, 5, 200),
            'bounty_id' => Bounty::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ACCEPTED',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'REJECTED',
        ]);
    }
}
