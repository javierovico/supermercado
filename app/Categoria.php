<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class Categoria extends Model
{
//    protected $appends = ['count_comments'];
    protected $casts = ['seleccionado' => 'integer'];

    public static function porPadreId($categoria_id){
        return self::addSelect(['cant_sub_cat' => function($query){
            $query->select([DB::raw('count(s_c.id)')])
                ->from('categorias','s_c')
                ->whereColumn('s_c.categoria_id','categorias.id');
        },
            'cant_prod' => function(Builder $query){
                $query->select([DB::raw('count(s_p.id)')])
                    ->from('categoria_producto','s_cp')
                    ->join('productos as s_p','s_p.id','=','s_cp.producto_id','left')
                    ->whereColumn('s_cp.categoria_id','categorias.id');
            }
        ])->where('categoria_id',$categoria_id)->get();

//        return Categoria::hydrate(DB::table('categorias','c')
//            ->select('c.*')
//            ->where('c.categoria_id',$categoria_id)
//            ->get()
//            ->all());

//        return DB
//            ::select('c.*,count(c2.id) as cantidad')
//            ->from('categorias c')
//            ->inner
//            ->where('categoria_id',$categoria_id)
//            ->get()->all();
    }

    /**
     * @param $categoriaId integer
     * @param $lista array {}
     */
    public static function updateProducto($categoriaId, $lista){
        $agregar = [];
        foreach ($lista as $item) {
            if($item->valor){   //add
                $agregar[] = ['categoria_id' => $categoriaId, 'producto_id' => $item->id];
            }else{              //rem
                DB::table('categoria_producto')->where('categoria_id','=',$categoriaId)->where('producto_id','=',$item->id)->delete();
            }
        }
        if(count($agregar)>0){
            DB::table('categoria_producto')->insert($agregar);
        }
    }

    public function productos(){
        return $this->belongsToMany(Producto::class);
    }

    public function subCategorias(){
        return $this->hasMany(Categoria::class);
    }

    public function categoriaPadre(){
        return $this->belongsTo(Categoria::class,'categoria_id','id');
    }
}
