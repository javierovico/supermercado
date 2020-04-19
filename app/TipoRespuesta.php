<?php


namespace App;


class TipoRespuesta
{

    public $success = true;
    public $error = [];
    public $warning = [];
    /**
     * @var mixed
     */
    public $dato = null;

    /**
     * @param $error
     * @return TipoRespuesta
     */
    static function respuestaConError($error){
        $tipoRespuesta = new TipoRespuesta();
        $tipoRespuesta->success = false;
        $tipoRespuesta->error[] = $error;
        return $tipoRespuesta;
    }

    public function toJSON(){
        return json_encode([
            'success' => $this->success,
            'error' => $this->error,
            'warings'   => $this->warning,
            'dato'  => $this->dato
        ]);
    }

    public function setError($string){
        $this->success = false;
        $this->error = $string;
    }

    public function setDato($res){
        if($res !== null){
            $this->dato = $res;
        }else{
            $this->success = false;
        }
        return $this->success;
    }

}
