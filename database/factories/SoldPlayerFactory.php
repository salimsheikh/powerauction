<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Player;
use App\Models\Team;
use App\Models\League;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SoldPlayer>
 */
class SoldPlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'player_id' => Player::factory(),
            'team_id' => Team::inRandomOrder()->first()?->id,
            'league_id' => League::inRandomOrder()->first()?->id,
            'category_id' => Category::inRandomOrder()->first()?->id,
            'sold_price' => 0,
            //'created_by' => 1, 
            //'updated_by' => 1, 
            'created_at' => now(), 
            'updated_at' => now(),
            
        ];
    }
}
