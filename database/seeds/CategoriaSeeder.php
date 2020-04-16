<?php

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
    }
}

class LectorFilas{
    public $archivo = '';
    public $ext = '';
    public $lineaActual = 0;
    public $cantidadColumnas = null; //para anotar la cantidad de columnas
    public $cantidadLinea = 0;
    //variables de excel
    public $ultimaColumna = 'A';
    public $activo = null;
    //variables de csv
    public $recurso = null;

    public function __construct($archivo){
        $this->archivo= $archivo;
        $arrdd = (explode(".", $archivo));
        $this->ext = $ext = end($arrdd);
        if(in_array($ext,['xls','xlsx'])){
            $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $excel2->load($archivo);
            $objPHPExcel->setActiveSheetIndex(0);
            $this->activo = $objPHPExcel->getActiveSheet();
            $this->cantidadLinea = $this->activo->getHighestRow();
            $this->ultimaColumna = $this->activo->getHighestColumn('1');
            $this->lineaActual = 0;
            $this->ext = 'xls';
            $this->cantidadColumnas = stringToNumberExcel($this->ultimaColumna);
        }else{
            $this->ext = 'csv';
            $this->recurso = @fopen($archivo,'r');
            while (!feof($this->recurso)){
                if($fila = fgets($this->recurso)){
                    if($this->cantidadColumnas == null){
                        $this->cantidadColumnas = count(explode(";",$fila));        //cantidad de columnas a leer
                    }
                    $this->cantidadLinea++;
                }
            }
            rewind($this->recurso);
        }
        $this->leerSiguiente();
    }

    /**
     * @return array|bool
     */
    public function leerSiguiente(){
        $lineaLeer = ($this->lineaActual);
        $cantidadLinea =$this->cantidadLinea;
        if($lineaLeer>=$cantidadLinea){  // 1 >= 1
            return false;
        }
        if($this->ext == 'xls'){
            $rango = 'A' . ($lineaLeer+1) . ':' .$this->ultimaColumna. ($lineaLeer+1);
            $arrAux = $this->activo->rangeToArray($rango);
            $array = [];
            foreach ($arrAux[0] as $cell) {
                $cell = ($cell==null)?'':$cell;
                if(is_float($cell)){
                    if(intval($cell)==$cell){
                        $cell = ''.intval($cell);
                    }else{
                        $cell = str_replace('.',',',''.$cell);
                    }
                }
                $array[] = $cell;
            }
        }else{
            if(!feof($this->recurso)){
                $array = explode(";",fgets($this->recurso));
            }else{
                return FALSE;
            }
        }
        $this->lineaActual++;
        if(count($array) == 0){
            return false;
        }else{
            return $array;
        }
    }

    public function leerSiguienteConColumnasExtras($extras){
        $array = $this->leerSiguiente();
        if($array){
            $array = array_merge($array,$extras);
        }
        return $array;
    }

    public function __destruct(){
        @fclose($this->recurso);
    }

    /**
     * Carga los valores brutos a los titulos (reescribe)
     * @param $titulos array de Titulos
     * @return bool
     */
    public function leerTitulosValores($titulos){
        $array = $this->leerSiguiente();
        if($array === FALSE){
            return FALSE;
        }else{
            /* @var $titulo Titulo*/
            foreach( $titulos as $titulo) {
                if($titulo->tieneColumna()){
                    $titulo->valorBruto = $array[$titulo->columnaIndex];
                }else if($titulo->tienValorEstatico()){
                    $titulo->valorBruto = $titulo->valorEstatico;
                }else{
                    $titulo->valorBruto = '';
                }
            }
        }
        return true;
    }
}
