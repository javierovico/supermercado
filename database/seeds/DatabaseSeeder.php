<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SuperSeeder::class);
        $this->call(ProductoDeliverySeeder::class);     //agrega el producto con codigo delivery
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CategorizacionNuevaSeeder::class);
    }
}
