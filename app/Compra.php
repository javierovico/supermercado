<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model{

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function productos(){
        return $this->belongsToMany(Producto::class)->withPivot(['precio_actual','cantidad'])->withTimestamps();
    }

}
