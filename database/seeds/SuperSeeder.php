<?php

use App\LectorFilas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        //Categorias
        $res = new LectorFilas(base_path().'/'.'prueba.csv');
        DB::table('tipo_categorias')->insert([
            'tipo' => 'generales',
            'id' => '1',
        ]);
        while ($fila = $res->leerSiguiente()){
            DB::table('categorias')->insert([
                'nombre' => $fila[1],
                'id' => $fila[0],
                'tipo_categoria_id' => 1,
            ]);
        }
        $res = new LectorFilas(base_path().'/'.'prueba2.csv');
        while ($fila = $res->leerSiguiente()){
            DB::table('categorias')->insert([
                'nombre' => $fila[2],
                'id' => 100+$fila[0],
                'categoria_id' => $fila[1],
                'tipo_categoria_id' => 1,
            ]);
        }
        $res = new LectorFilas(base_path().'/'.'prueba3.csv');
        while ($fila = $res->leerSiguiente()){
            DB::table('categorias')->insert([
                'nombre' => $fila[3],
                'id' => 1000+$fila[0],
                'categoria_id' => 100+$fila[2],
                'tipo_categoria_id' => 1,
            ]);
        }
        //Tipo Producto
        $res = new LectorFilas(base_path().'/'.'tipo_producto.csv');
        while ($fila = $res->leerSiguiente()){
            DB::table('tipo_medida_productos')->insert([
                'id' => $fila[0],
                'tipo' => $fila[1],
            ]);
        }

        //Productos
        $res = new LectorFilas(base_path().'/'.'infprod_csv.csv');
        while ($fila = $res->leerSiguiente()){
            $tipoProd= -1;
            switch (strtoupper($fila[2])){
                case '0':
                case '1':
                case 'DESA':
                case 'UNIDAD':
                case 'UNIDADES':
                case 'LITROS':
                    $tipoProd = 1;
                    break;
                case 'PESABLE':
                    $tipoProd = 2;
                    break;
                default:
                    echo ('NO SE PUEDE ' .$fila[2] . ' '. $res->lineaActual);
            }
            DB::table('productos')->insert([
//                'id' => $fila[0],
                'codigo' => $fila[0],
                'nombre' => ucfirst(strtolower($fila[1])),
                'linea' => $fila[3],
                'impuesto' => $fila[4],
                'descuento' => $fila[5],
                'precio' => str_replace('.','',$fila[6]),
                'precio_mayorista' => str_replace('.','',$fila[7]),
                'precio_costo' => str_replace('.','',$fila[8]),
                'tipo_medida_producto_id' => $tipoProd
            ]);
//            DB::table('categoria_producto')->insert([
//                'categoria_id' => $fila[1],
//                'producto_id' => $fila[0],
//            ]);
        }
    }
}
