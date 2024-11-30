<?php
namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition()
    {
        ////UPDATE players SET category_id = FLOOR(1 + (RAND() * 4)) -- Random category_id between 1 and 4 WHERE category_id > 4;
        return [
            'uniq_id' => "SPL/".$this->faker->randomNumber(1,100),
            'player_name' => $this->faker->name(),
            'nickname' => $this->faker->word(),
            'mobile' => $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail(),
            'category_id' => $this->faker->numberBetween(1,4),
            'dob' => $this->faker->date('Y-m-d'),
            'image' => $this->faker->imageUrl(80, 80, 'sports'),
            'image_thumb' => $this->faker->imageUrl(100, 100, 'sports'),
            'bat_type' => $this->faker->randomElement(['Right', 'Left']),
            'ball_type' => $this->faker->randomElement(['Fast', 'Spin']),
            'type' => $this->faker->randomElement(['batsman', 'bowler', 'all-rounder']),
            'profile_type' => $this->faker->randomElement(['men', 'women','senior-citizen']),
            'style' => $this->faker->randomElement(['heft_hand_batsman', 'right_hand_batsman', 'left_hand_bowler','right_hand_bowler']),
            'last_played_league' => $this->faker->word(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'status' => $this->faker->randomElement(['publish']),
            'order_id' => $this->faker->numberBetween(1, 999),
            'created_by' => $this->faker->randomNumber(1, 1),
            'updated_by' => $this->faker->randomNumber(1, 1),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        //php artisan tinker
        //\App\Models\Player::factory()->count(10)->create();
    }
}
