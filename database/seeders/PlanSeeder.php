<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample data into the plans table
        DB::table('plans')->insert([
            [
                'slug' => Str::slug('Manual'),
                'name' => __('Manual'),
                'amount' => '0',
                'status' => 'publish',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('silver'),
                'name' => __('Silver'),
                'amount' => '300',
                'status' => 'publish',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Str::slug('gold'),
                'name' => __('Gold'),
                'amount' => '500',
                'status' => 'publish',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
