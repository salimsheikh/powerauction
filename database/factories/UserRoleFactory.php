<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserRole>
 */
class UserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'name' => $this->faker->word,
            'permission' => json_encode(['view', 'edit', 'delete']), // Example permissions
            'description' => $this->faker->sentence,
            'created_by' => 1, // Default user ID
            'updated_by' => null,
        ];
    }
}
