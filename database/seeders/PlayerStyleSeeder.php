<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerStyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ["style" => "left_hand_batsman", "name" => "Left Hand Batsman", "order" => 1],
            ["style" => "right_hand_batsman", "name" => "Right Hand Batsman", "order" => 2],
            ["style" => "left_hand_bowler", "name" => "Left Hand Bowler", "order" => 3],
            ["style" => "right_hand_bowler", "name" => "Right Hand Bowler", "order" => 4]
        ];

        foreach ($items as $item) {
            PlayerStyle::create($item);
        }
    }
}
