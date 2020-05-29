<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


/**
 * @method Compra find($compraId)
 * @property string estado : car: carrito,
 * @property User user
 */
class Compra extends Model{

    public const TIPO_CARRITO = 'car';

    protected $appends = ['pago_total'];
    protected $casts = ['updated_at' => 'timestamp'];

    //2020-05-15T18:33:50.000000Z
//    public function getUpdatedAtAttribute($date)
//    {
//        return $date->format('Y-m-d');
//    }
    public static function detallePagosUsuarios(){
        return self::query()
            ->where('estado','<>',self::TIPO_CARRITO)
            ->with('user')
            ->orderBy('updated_at','desc');
    }

    public static function detallePagosPersonal($userId){
        return self::query()
            ->where('estado','<>',self::TIPO_CARRITO)
            ->where('user_id','=',$userId)
//            ->withCount(['productos' => function(Builder $q){
//                $q->sum('precio_actual');
//            }])
            ->orderBy('updated_at','desc');
    }

    public static function carritoCompra(User $user){
        $carrito =  self::query()
            ->where('pagado',false)
            ->where('estado','=',self::TIPO_CARRITO)
            ->where('user_id',$user->id)
            ->first();
        if(null == $carrito){
            $carrito = new Compra();
            $carrito->estado = self::TIPO_CARRITO;
            $carrito->user()->associate($user);
            $carrito->save();
        }
        return $carrito;
    }

    public function getPagoTotalAttribute(){
        $suma = $this->productos()->get()->sum(function($t){
//            dd($t->pivot);
//            $d = $t->pivot->precio;
            return $t->pivot->precio_actual * $t->pivot->cantidad;
        });
        return $suma;
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function productos(){
        return $this->belongsToMany(Producto::class)->withPivot(['precio_actual','cantidad','id'])->withTimestamps();
    }

    public function pagos(){
        return $this->hasMany(Pago::class);
    }

    public function precioTotal(){
        $total = 0;
        /** @var Producto $producto */
        foreach($this->productos as $producto){
            $total += $producto->precio * $producto->pivot->cantidad;
        }
        return $total;
    }

    public function actualizarPreciosIntermedios(){
        foreach ($this->productos as $producto){
            $this->productos()->updateExistingPivot($producto,[
                'precio_actual' => $producto->precio
            ]);
        }
    }

    public function agregarProducto(Producto $producto, $cantidad){
        //Ver si ya existia ese producto
        /** @var Producto $productoExistente */
        if($productoExistente = $this->productos()->where('producto_id',$producto->id)->first()){
            $this->productos()->updateExistingPivot($productoExistente->id,[
                'cantidad' => $cantidad + $productoExistente->pivot->cantidad,
                'precio_actual' => $producto->precio * ($cantidad + $productoExistente->pivot->cantidad),
            ]);
        }else{
            $this->productos()->attach($producto->id,[
                'cantidad' => $cantidad,
                'precio_actual' => $cantidad * $producto->precio,
            ]);
        }
    }

}
