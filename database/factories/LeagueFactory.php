<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\League>
 */
class LeagueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'league_name' => $this->faker->unique()->name,
            'description' => $this->faker->text,            
            'auction_view' => 0,
            'category' => null,
            'unsold' => 0,
            'status' => 0, 
            'created_by' => 1, 
            'updated_by' => 1, 
            'created_at' => now(), 
            'updated_at' => now(),
        ];
    }
}
