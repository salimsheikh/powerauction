<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlayerType;

class PlayerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ["type" => "batsman", "name" => "Batsman", "order" => 1,'created_by'=>1],
            ["type" => "bowler", "name" => "Bowler", "order" => 2,'created_by'=>1],
            ["type" => "all-rounder", "name" => "All Rounder", "order" => 3,'created_by'=>1],
        ];

        foreach ($items as $item) {
            PlayerType::create($item);
        }
    }
}
