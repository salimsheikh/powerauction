<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlayerStyle;

class PlayerStyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ["style" => "left_hand_batsman", "name" => "Left Hand Batsman", "order" => 1,'created_by'=>1],
            ["style" => "right_hand_batsman", "name" => "Right Hand Batsman", "order" => 2,'created_by'=>1],
            ["style" => "left_hand_bowler", "name" => "Left Hand Bowler", "order" => 3,'created_by'=>1],
            ["style" => "right_hand_bowler", "name" => "Right Hand Bowler", "order" => 4,'created_by'=>1]
        ];

        foreach ($items as $item) {
            PlayerStyle::create($item);
        }
    }
}
