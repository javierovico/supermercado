<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
//    public static function categoriasPadres(){
//        return self::where('categoria_id',null)->get()->all();
//    }

    public static function porPadreId($categoria_id){
        return self::where('categoria_id',$categoria_id)->get()->all();
    }

    public function productos(){
        return $this->belongsToMany('App\Producto');
    }

    public function subCategorias(){
        return $this->hasMany('App\Categoria');
    }

    public function categoriaPadre(){
        return $this->belongsTo('App\Categoria','categoria_id','id');
    }
}
