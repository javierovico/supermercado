<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolDeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $role = new Role();
        $role->name = Role::NOMBRE_DELIVERY;
        $role->description = 'Delivery';
        $role->save();
        $user = new User();
        $user->name = 'Delivery';
        $user->email = 'delivery@delsuper.com.py';
        $user->password = Hash::make('del1Very');
        $user->save();
        $user->roles()->attach(Role::where('name',Role::NOMBRE_DELIVERY)->first());
    }
}
