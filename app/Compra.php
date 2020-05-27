<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Compra find($compraId)
 * @property string estado : car: carrito,
 */
class Compra extends Model{

    protected $appends = ['pago_total'];
    protected $casts = ['updated_at' => 'timestamp'];

    //2020-05-15T18:33:50.000000Z
//    public function getUpdatedAtAttribute($date)
//    {
//        return $date->format('Y-m-d');
//    }

    public function getPagoTotalAttribute(){
        $suma = $this->pagos()->where('response_code','00')->sum('precio');
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
        /** @var Producto $producto */
        if($producto = $this->productos()->where('id',$producto->id)->first()){
            $this->productos()->updateExistingPivot($producto->id,[
                'cantidad' => $cantidad + $producto->pivot->cantidad,
                'precio' => $producto->precio
            ]);
        }else{
            $this->productos()->attach($producto->id,[
                'cantidad' => $cantidad,
                'precio_actual' => $cantidad * $producto->precio,
            ]);
        }


        if($compraProducto == null){
            $ultimaCompra->productos()->attach($producto->id,[
                'cantidad' => $cantidad,
                'precio_actual' => 0
            ]);
            $ultimaCompra->save();
        }else{
            $compraProducto->cantidad += $cantidad;
            $compraProducto->save();
        }
    }

}
