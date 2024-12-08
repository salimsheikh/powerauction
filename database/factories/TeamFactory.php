<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\League;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $team_name = $this->faker->unique()->name()  . ' ' . $this->faker->randomNumber();
        $image = $this->faker->imageUrl(80, 80, 'sports');
        return [
            'team_name' => $team_name, 
            'team_logo' => $image, 
            'team_logo_thumb' => $image, 
            'virtual_point' => 0,
            'league_id' => League::inRandomOrder()->first()?->id, // Fetches a random existing ID
            'owner_id' => User::inRandomOrder()->first()?->id, // Fetches a random existing ID
            'status' => 'publish', 
            'created_by' => 1, 
            'updated_by' => 1, 
            'created_at' => now(), 
            'updated_at' => now(),
        ];
    }
}
