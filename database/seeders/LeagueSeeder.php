<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample data into the league table
        DB::table('league')->insert([
            [
                'league_name' => 'Levels premier league 2024 Mens',
                'description' => __('levels underarm box cricket tournament'),
                'auction_view' => '0',                
                'status' => '1',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'league_name' => 'League B',
                'description' => __('levels underarm box cricket tournament'),
                'auction_view' => '0',                
                'status' => '1',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'league_name' => 'League C',
                'description' => __('levels underarm box cricket tournament'),
                'auction_view' => '0',                
                'status' => '1',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'league_name' => 'League D',
                'description' => __('levels underarm box cricket tournament'),
                'auction_view' => '0',                
                'status' => '1',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
