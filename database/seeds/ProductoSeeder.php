<?php

use App\LectorFilas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $res = new LectorFilas(base_path().'/'.'productos.csv');
        $res->leerSiguiente();  //saca la cabezera
        $id = 1;
        while ($fila = $res->leerSiguiente()){
            DB::table('productos')->insert([
                'nombre' => $fila[1],
                'id' => $id,
                'precio' => $fila[2],
            ]);
            DB::table('categoria_producto')->insert([
                'categoria_id' => $fila[0],
                'producto_id' => $id,
            ]);
            $id++;
        }
    }
}
