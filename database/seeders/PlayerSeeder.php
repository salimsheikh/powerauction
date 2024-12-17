<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('players')->insert([
            [
                'id' => 1,
                'uniq_id' => 'SPL/1',
                'player_name' => 'Rashid',
                'nickname' => 'Rashidweb',
                'mobile' => null,
                'email' => 'rashid.makent@gmail.com',
                'category_id' => 1,
                'dob' => '1980-07-06',
                'image' => '3.jpg',
                'image_thumb' => '3.jpg',
                'bat_type' => null,
                'ball_type' => null,
                'type' => 'all-rounder',
                'profile_type' => 'senior-citizen',
                'style' => 'right_hand_batsman',
                'last_played_league' => '-',
                'address' => 'Byculla',
                'city' => 'Mumbai',
                'status' => 'publish',
                'order_id' => 9999,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'uniq_id' => 'SPL/2',
                'player_name' => 'Akash',
                'nickname' => 'AK',
                'mobile' => '9876543210',
                'email' => 'akash.sports@gmail.com',
                'category_id' => 2,
                'dob' => '1990-08-15',
                'image' => '2.jpg',
                'image_thumb' => '2.jpg',
                'bat_type' => 'heavy',
                'ball_type' => 'spin',
                'type' => 'batsman',
                'profile_type' => 'men',
                'style' => 'left_hand_batsman',
                'last_played_league' => 'League 2024',
                'address' => 'Andheri',
                'city' => 'Mumbai',
                'status' => 'publish',
                'order_id' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'uniq_id' => 'SPL/3',
                'player_name' => 'Sneha',
                'nickname' => 'Sneh',
                'mobile' => '9876543222',
                'email' => 'sneha.batsman@gmail.com',
                'category_id' => 3,
                'dob' => '2000-03-15',
                'image' => '1.jpg',
                'image_thumb' => '1.jpg',
                'bat_type' => 'light',
                'ball_type' => 'fast',
                'type' => 'bowler',
                'profile_type' => 'senior-citizen',
                'style' => 'right_hand_bowler',
                'last_played_league' => 'League 2023',
                'address' => 'Dadar',
                'city' => 'Mumbai',
                'status' => 'publish',
                'order_id' => 7777,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
