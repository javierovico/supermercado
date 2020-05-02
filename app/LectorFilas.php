<?php


namespace App;


class LectorFilas
{

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
                $array = fgetcsv($this->recurso,0,';');//explode(";",changeSemiColonByColon(fgets($this->recurso)));
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
