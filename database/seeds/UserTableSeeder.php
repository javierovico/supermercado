<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $user = new User();
        $user->name = 'Financiero';
        $user->email = 'financiero@delsuper.com.py';
        $user->password = Hash::make('f1nanc1Er0');
        $user->save();
        $user->roles()->attach(Role::where('name', 'user')->first());
        $user->roles()->attach(Role::where('name', 'financiero')->first());
//admin
        $user = new User();
        $user->name = 'Administrador';
        $user->email = 'administrador@delsuper.com.py';
        $user->password = Hash::make('adm!nIstrd0R');
        $user->save();
        $user->roles()->attach(Role::where('name', 'user')->first());
        $user->roles()->attach(Role::where('name', 'admin')->first());
    }
}
