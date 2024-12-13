<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'support@makent.in';
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => __('Nexgeno Team'), 
                'email' => $email,
                'password' => bcrypt($email)
            ]);
        }
    
        $role = Role::create(['name' => 'Administrator']);
     
        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);

        $email = 'salimsheikh4u2000@gmail.com';
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => __('Salim Shaikh'), 
                'email' => $email,
                'password' => bcrypt($email)
            ]);
        }
    
        $role = Role::create(['name' => 'User']);
     
        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);

        $role = Role::create(['name' => 'Player']);
        $role = Role::create(['name' => 'Subscriber']);
    }
}
