<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method Pago find($get)
 */
class Pago extends Model{

    protected $fillable = ['precio'];

    public function compra(){
        return $this->belongsTo(Compra::class);
    }
}
