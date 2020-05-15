<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method Compra find($compraId)
 */
class Compra extends Model{

    public function user(){
        return $this->belongsTo(User::class);
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

}
