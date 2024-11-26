<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SponsorType;

class SponsorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['slug' => 'premium', 'name' => __('Premium'), "order" => 1, 'status'=>'publish','created_by'=>1],
            ['slug' => 'gold', 'name' => __('Gold'), "order" => 2, 'status'=>'publish','created_by'=>1],
        ];

        foreach ($types as $type) {
            SponsorType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}

