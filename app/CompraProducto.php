<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompraProducto extends Model
{
    protected $table = 'compra_producto';

    public function compra(){
        return $this->belongsTo(Compra::class);//,'compra_id','id');
    }
}
