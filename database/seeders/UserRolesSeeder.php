<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'slug' => 'administrator',
                'name' => __('Administrator'),
                'permission' => json_encode(['all']),
                'description' => 'Administrator role with full access',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),   
            ],
            [
                'slug' => 'user',
                'name' => __('User'),
                'permission' => json_encode(['view']),
                'description' => 'General user role with limited access',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),   
            ],
            [
                'slug' => 'player',
                'name' => __('Player'),
                'permission' => json_encode(['view']),
                'description' => 'Player role with gaming access',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),   
            ],
            [
                'slug' => 'subscriber',
                'name' => __('Subscriber'),
                'permission' => json_encode(['view']),
                'description' => 'General user role with limited access',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),   
            ],

            
        ];

        DB::table('user_roles')->insert($roles);
    }
}
