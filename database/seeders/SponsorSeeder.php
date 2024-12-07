<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample data into the league table
        DB::table('sponsors')->insert([            
            'sponsor_name' => __('Sponsor 1'),
            'sponsor_logo' => 'sponsors_1.jpg',
            'sponsor_description' => 'Sponsors Text',
            'sponsor_type' => 'premium',            
            'status' => 'publish',
            'created_at' => now(),
            'updated_at' => now(),            
        ]);
        

        DB::table('sponsors')->insert([            
            'sponsor_name' => __('Warriors'),
            'sponsor_logo' => 'sponsors_2.jpg',
            'sponsor_description' => 'Sponsors Text',
            'sponsor_type' => 'premium',            
            'status' => 'publish',
            'created_at' => now(),
            'updated_at' => now(),            
        ]);

        DB::table('sponsors')->insert([            
            'sponsor_name' => __('Champions'),
            'sponsor_logo' => 'sponsors_3.jpg',
            'sponsor_description' => 'Sponsors Text',
            'sponsor_type' => 'gold',            
            'status' => 'publish',
            'created_at' => now(),
            'updated_at' => now(),            
        ]);
    }
}
