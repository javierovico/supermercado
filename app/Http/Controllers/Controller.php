<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $rol string|array
     * @return bool
     */
    public function autorizar($rol){
        $user = Auth::user();
        if(!$user){
            abort(401,"tenes que iniciar sesion");
            return false;
        }else if(!$user->hasAnyRole($rol)){
            $rolString = is_array($rol)?(implode(' o ',$rol)):$rol;
            abort(401,"Necesitas privilegios de $rolString");
            return false;
        }else{
            return true;
        }
    }
}
