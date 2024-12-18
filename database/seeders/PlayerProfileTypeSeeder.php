<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerProfileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ["profile_type" => "men", "name" => "Men", "order" => 1],
            ["profile_type" => "women", "name" => "Women", "order" => 2],
            ["profile_type" => "senior-citizen", "name" => "Senior Citizen", "order" => 3]
        ];

        foreach ($items as $item) {
            PlayerProfileType::create($item);
        }
    }
}
