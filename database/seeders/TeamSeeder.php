<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insertedId = DB::table('users')->insertGetId([            
                'name' => __('Nexgeno Team'),                
                'phone' => '+91123456789',                
                'address' => 'Kurla',
                'email' => 'support@makent.in',
                'role' => 'administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('support@makent.in'),
                'remember_token' => Str::random(10),
                'created_by'=> 1,
                'updated_by'=> 1,
                'created_at' => now(),
                'updated_at' => now(),            
        ]);

        // Insert sample data into the league table
        DB::table('teams')->insert([            
                'team_name' => __('Nexgeno Team'),
                'team_logo' => 'teams_1.jpg',
                'team_logo_thumb' => 'teams_1_thumb.jpg',                
                'virtual_point' => '0',
                'league_id' => '4',
                'owner_id' => $insertedId,
                'status' => 'publish',
                'created_at' => now(),
                'updated_at' => now(),            
        ]);


        $insertedId = DB::table('users')->insertGetId([            
                'name' => __('Salim Shaikh'),                
                'phone' => '+919876543210',                
                'address' => 'Kurla',
                'email' => 'salimsheikh4u2000@gmail.com',
                'role' => 'administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('salimsheikh4u2000@gmail.com'),
                'remember_token' => Str::random(10),
                'created_by'=> 1,
                'updated_by'=> 1,
                'created_at' => now(),
                'updated_at' => now(),            
        ]);
        

        
    }
}
