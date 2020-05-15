<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method Pago find($get)
 * @method static findOrFail($pagoId)
 */
class Pago extends Model{

    protected $fillable = ['precio'];

    public function compra(){
        return $this->belongsTo(Compra::class);
    }
}
