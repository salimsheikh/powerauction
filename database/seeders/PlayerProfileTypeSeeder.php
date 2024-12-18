<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlayerProfileType;

class PlayerProfileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ["profile_type" => "men", "name" => "Men", "order" => 1,'created_by'=>1],
            ["profile_type" => "women", "name" => "Women", "order" => 2,'created_by'=>1],
            ["profile_type" => "senior-citizen", "name" => "Senior Citizen", "order" => 3,'created_by'=>1]
        ];

        foreach ($items as $item) {
            PlayerProfileType::create($item);
        }
    }
}
