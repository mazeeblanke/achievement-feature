<?php

namespace Database\Factories;

use App\Models\AchievementType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievements>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence(),
            'qualifier' => 10,
            'achievement_type_id' => AchievementType::factory()->create()->id,
        ];
    }
}
