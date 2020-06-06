<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @property string description
 */
class Role extends Model{

    const NOMBRE_DELIVERY = 'delivery';
    const NOMBRE_USUARIO = 'user';
    protected $fillable = ['name','descripcion'];

    public function users(){
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
