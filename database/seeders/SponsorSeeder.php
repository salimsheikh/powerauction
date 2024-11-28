<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample data into the league table
        DB::table('teams')->insert([            
            'sponsor_name' => __('Sponsor 1'),
            'sponsor_logo' => 'sponsors_3.jpg',
            'sponsor_description' => 'Sponsors Text',
            'sponsor_type' => 'premium',            
            'status' => 'publish',
            'created_at' => now(),
            'updated_at' => now(),            
    ]);
    }
}
