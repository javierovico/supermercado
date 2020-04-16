<?php

use App\Categoria;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    return view('welcome');
    foreach(Categoria::where('categoria_id',null)->get() as $cate){
        echo $cate->nombre . '<br>';
        foreach(Categoria::where('categoria_id',$cate->id)->get() as $cate2){
            echo '---------------------------------------------'.$cate2->nombre . '<br>';
            foreach(Categoria::where('categoria_id',$cate2->id)->get() as $cate3){
                echo '------------------------------------------------------------------------------------------------------------------------'.$cate3->nombre . '<br>';
            }
        }
    }
});
