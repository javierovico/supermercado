<?php

use App\Categoria;
use App\Producto;
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

//Route::get('/', function () {
//
//    return view('welcome');
////    foreach(Categoria::where('categoria_id',null)->get() as $cate){
////        echo $cate->nombre . '<br>';
////        foreach(Categoria::where('categoria_id',$cate->id)->get() as $cate2){
////            echo '---------------------------------------------'.$cate2->nombre . '<br>';
////            foreach(Categoria::where('categoria_id',$cate2->id)->get() as $cate3){
////                echo '------------------------------------------------------------------------------------------------------------------------'.$cate3->nombre . '<br>';
////                foreach($cate3->productos as $cate4){
////                    echo '------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'.$cate4->nombre .' (' .$cate4->precio. ')<br>';
////                }
////            }
////        }
////    }
//});

Route::get('/', 'MainController@index')->name('index');
Route::get('/prueba', function (){
    return view('prueba');
});
Route::apiResource('/producto', 'ProductoController');
Route::get('/categoria/vista', 'CategoriaController@vista')->name('categoria.vista');

Route::post('/categoria/updateProductosList', 'CategoriaController@updateProductosList');
Route::apiResource('/categoria', 'CategoriaController');


Auth::routes(/*['register'=>false]*/);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/checkUser', 'HomeController@checkUser')->name('checkUser');

//Route::apiResource('thoughts', 'ProductoApiController');
