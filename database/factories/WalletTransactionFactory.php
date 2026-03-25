<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletTransactionFactory extends Factory
{
    public function definition(): array
    {
        $types = ['DEPOSIT', 'WITHDRAWAL', 'PAYMENT', 'EARNING'];

        return [
            'type' => fake()->randomElement($types),
            'amount' => fake()->randomFloat(2, -100, 100),
            'description' => fake()->optional()->sentence(),
            'stripe_payment_id' => fake()->optional()->uuid(),
            'user_id' => User::factory(),
        ];
    }

    public function deposit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'DEPOSIT',
            'amount' => abs(fake()->randomFloat(2, 10, 500)),
        ]);
    }

    public function withdrawal(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'WITHDRAWAL',
            'amount' => -abs(fake()->randomFloat(2, 10, 200)),
        ]);
    }

    public function earning(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'EARNING',
            'amount' => abs(fake()->randomFloat(2, 5, 200)),
        ]);
    }

    public function payment(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'PAYMENT',
            'amount' => -abs(fake()->randomFloat(2, 5, 200)),
        ]);
    }
}
