<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call your specific seeders
        $this->call(CategorySeeder::class);
        $this->call(LeagueSeeder::class);
        $this->call(PlanSeeder::class);        
        $this->call(SponsorTypeSeeder::class);
        $this->call(SponsorSeeder::class);
        $this->call(StyleSeeder::class);
        $this->call(PlayerProfileTypeSeeder::class);
        $this->call(PlayerStyleSeeder::class);
        $this->call(PlayerTypeSeeder::class);
        $this->call(PlayerSeeder::class);

        $this->call(TeamSeeder::class);
        $this->call(UserSeeder::class);
        // $this->call(UserRolesSeeder::class);

        $this->call(PermissionTableSeeder::class);
        $this->call(CreateAdminUserSeeder::class);

        /*
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'editor']);
        Permission::create(['name' => 'manage posts']);
        Permission::create(['name' => 'edit posts']);

        $admin = User::find(1);
        $admin->assignRole('admin');
        $admin->givePermissionTo('manage posts');
        */
    }
}
