<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
    //
    public static function porCategoria($categoria_id){
        return Producto::hydrate(DB::table('categoria_producto','cp')
            ->where('cp.categoria_id',$categoria_id)
            ->select('p.*')
            ->join('productos as p','p.id','=','cp.producto_id','inner')
            ->get()
            ->all()
        );
    }

    /**
     * @param $palabraClave
     * @param $categoriaId int si es distinto de nulo, trae si esta seleccionado
     * @param $limiteInferior
     * @param $cantidad
     * @return mixed
     */
    public static function porPalabraClave($palabraClave,$categoriaId = null,$limiteInferior = 0, $cantidad = 10){
        return Producto::addselect([
            'seleccionado' => function(Builder $query) use ($categoriaId) {
                $query->selectRaw('count(*)')
                    ->from('categoria_producto','cp')
                    ->whereColumn('cp.producto_id','=','productos.id')
                    ->where('cp.categoria_id','=',$categoriaId);
            }
        ])->where('nombre','like','%'.$palabraClave.'%')->skip($limiteInferior)->take($cantidad)->get()->all();
    }

    public static function getCantidad($palabraClave){
        return DB::table('productos')->selectRaw('count(*) total')->where('nombre','like','%'.$palabraClave.'%')->get()->first()->total;
    }
}
