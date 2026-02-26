<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company' => fake()->company(),
            'position' => fake()->jobTitle(),
            'location' => fake()->city(),
            'status' => fake()->randomElement(['applied', 'interview', 'offer', 'rejected']),
            'applied_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'next_followup_at' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
