<?php

use App\LectorFilas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $res = new LectorFilas(base_path().'/'.'prueba.csv');
//        DB::table('tipo_categorias')->insert([
//            'tipo' => 'generales',
//            'id' => '1',
//        ]);
//
//        while ($fila = $res->leerSiguiente()){
//            DB::table('categorias')->insert([
//                'nombre' => $fila[1],
//                'id' => $fila[0],
//                'tipo_categoria_id' => 1,
//            ]);
//        }
//        $res = new LectorFilas(base_path().'/'.'prueba2.csv');
//        while ($fila = $res->leerSiguiente()){
//            DB::table('categorias')->insert([
//                'nombre' => $fila[2],
//                'id' => 100+$fila[0],
//                'categoria_id' => $fila[1],
//                'tipo_categoria_id' => 1,
//            ]);
//        }
//        $res = new LectorFilas(base_path().'/'.'prueba3.csv');
//        while ($fila = $res->leerSiguiente()){
//            DB::table('categorias')->insert([
//                'nombre' => $fila[3],
//                'id' => 1000+$fila[0],
//                'categoria_id' => 100+$fila[2],
//                'tipo_categoria_id' => 1,
//            ]);
//        }
    }
}
