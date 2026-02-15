<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Followup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Followup>
 */
class FollowupFactory extends Factory
{
    protected $model = Followup::class;

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'type' => fake()->randomElement(['email', 'call', 'linkedin']),
            'done_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
