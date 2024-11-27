<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Player;
use App\Models\Plan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Category::factory(10)->create();
        //Player::factory()->count(50)->create(); // Create 50 players


        // Call your specific seeder
        // $this->call(SettingsTableSeeder::class);
        // $this->call(PlayerSeeder::class);

        $this->call(PlayerSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SponsorTypeSeeder::class);
        $this->call(PlanSeeder::class);
    }
}
