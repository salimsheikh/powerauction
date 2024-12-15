<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = ['player-details-view','team-add-player','team-remve-player','team-booster','team-view-player','league-auction'];

        $menus = config('menus'); // Or fetch from the database
        foreach($menus as $menu){
            $permissions[] = $menu['permission'];
        }      

        $masters = ['category','player','team','league','sponsor','user','user-role','user-permission'];
        $subPermissions = ['page-view','list','create','edit','update','delete'];

        foreach($masters as $m){
            foreach($subPermissions as $p){
                $permissions[] =   $m."-".$p;
            }
        }

        $permissions = array_values(array_unique($permissions));

        sort($permissions);       
      
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
