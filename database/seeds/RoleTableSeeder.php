<?php

use App\Role;
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
        $role = new App\Role();
        $role->name = 'admin';
        $role->description = 'Administrator';
        $role->save();
        $role = new App\Role();
        $role->name = 'user';
        $role->description = 'User';
        $role->save();
    }
}
