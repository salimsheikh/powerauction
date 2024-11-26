<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'category_name' => 'Master',
                'base_price' => 299.00,
                'description' => 'Players',
                'color_code' => '#4b4ba0',
                'status' => 'publish',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category_name' => 'Blaster',
                'base_price' => 299.00,
                'description' => 'Players',
                'color_code' => '#1616ca',
                'status' => 'publish',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'category_name' => 'Queen',
                'base_price' => 299.00,
                'description' => 'Players',
                'color_code' => '#1c1cc4',
                'status' => 'publish',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'category_name' => 'Captain',
                'base_price' => 299.00,
                'description' => 'Players',
                'color_code' => '#2f2fc6',
                'status' => 'publish',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
