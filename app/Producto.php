<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @property Categoria $categorias
 * @method Producto find($id)
 */
class Producto extends Model{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['nombre','codigo','impuesto','descuento','stock','linea','thumbnail','contenido','tipo_medida_producto_id','precio','precio_mayorista','precio_costo'];

    protected $casts = [
        'seleccionado' => 'integer',
        'tipo_medida_producto' => 'integer',
    ];

    public function categorias(){
        return $this->belongsToMany(Categoria::class);
    }

    public function getNombreArchivo(){
        return $this->codigo . '.jpg';
    }


    public static function porCategoria($categoria_id){
        return Producto::hydrate(DB::table('categoria_producto','cp')
            ->where('cp.categoria_id',$categoria_id)
            ->select('p.*')
            ->join('productos as p','p.id','=','cp.producto_id','inner')
            ->get()
            ->all()
        );
    }

    public function tipoMedida(){
        return $this->belongsTo(TipoMedidaProducto::class,'tipo_medida_producto_id','id');
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
        if($palabraClave !==null){
            return DB::table('productos')->selectRaw('count(*) total')->where('nombre','like','%'.$palabraClave.'%')->get()->first()->total;
        }else{
            return DB::table('productos')->selectRaw('count(*) total')->get()->first()->total;
        }

    }

    public static function porLimite(int $limiteInferior, int $cantidad){
        return Producto::skip($limiteInferior)->take($cantidad)->get()->all();
    }

    /**
     * @param $thumbnailString string
     */
    public function setThumbnail($thumbnailString){
        if(Storage::disk('s3')->exists($this->getNombreArchivo())){
            Storage::disk('s3')->delete($this->getNombreArchivo());
            Storage::disk('s3')->put($this->getNombreArchivo(),$thumbnailString);
        }else{
            Storage::disk('s3')->put($this->getNombreArchivo(),$thumbnailString);
        }
    }
}
