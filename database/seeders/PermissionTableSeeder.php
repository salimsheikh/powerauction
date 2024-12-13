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
        $permissions = [];

        $menus = config('menus'); // Or fetch from the database
        foreach($menus as $menu){
            $permissions[] = $menu['permission'];
        }      

        $masters = ['category','player','team','league','sponsor','user','role','permission'];
        $subPermissions = ['page-page','create','edit','update','delete'];

        foreach($masters as $m){
            foreach($subPermissions as $p){
                $permissions[] =   $m."-".$p;
            }
        }
      
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
