<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_station = new Role();
        $role_station->name = 'station';
        $role_station->description = 'A Station User';
        $role_station->save();
        
        $role_company = new Role();
        $role_company->name = 'company';
        $role_company->description = 'A Company User';
        $role_company->save();
    }
}
