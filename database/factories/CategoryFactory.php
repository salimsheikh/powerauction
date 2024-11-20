<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category_name = $this->faker->unique()->name()  . ' ' . $this->faker->randomNumber();
        return [
            'category_name' => $category_name, 
            'base_price' => rand(5, 100) . "." . rand(0, 99), 
            'description' => $this->faker->sentence, 
            'color_code' => sprintf("#%06X", mt_rand(0, 0xFFFFFF)),
            'status' => 'publish', 
            'created_by' => 1, 
            'updated_by' => 1, 
            'created_at' => now(), 
            'updated_at' => now(),
        ];
    }
}
