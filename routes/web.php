<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/front/{config}','MainController@index')->where('config','.*');
Route::get('/', 'MainController@index')->name('index');
Route::get('/carrito/historial/detalle/{id}', 'MainController@index');
Route::get('/carrito/historial', 'MainController@index');
Route::get('/carrito', 'MainController@index');
Route::get('/financiero/pagos', 'MainController@index');
Route::get('/iniciar-sesion', 'MainController@index');
Route::get('/registrarse', 'MainController@index');
Route::get('/admin/productos', 'MainController@index');
Route::get('/admin/categorias', 'MainController@index');

Route::get('/producto/lista', 'ProductoController@listaRecursiva');
Route::post('/producto/thumbnail/{id}', 'ProductoController@thumbnail');
Route::post('/producto/updateCategoriasList/{id}', 'ProductoController@updateCategorias');
Route::apiResource('/producto', 'ProductoController');

Route::get('/categoria/vista', 'CategoriaController@vista')->name('categoria.vista');
Route::get('/cat/{nombre}', 'CategoriaController@catName');
Route::get('/cat', 'CategoriaController@catName');
Route::get('/categoria/byProducto/{productoId}', 'CategoriaController@byProducto')->where('productoId','[0-9]+');
Route::post('/categoria/updateProductosList', 'CategoriaController@updateProductosList');
Route::get('/categoria/listaOrdenada', 'CategoriaController@listaOrdenada');
Route::get('/categoria/catSel', 'CategoriaController@catSel');
Route::apiResource('/categoria', 'CategoriaController');

Route::apiResource('/compraProducto','CompraProductoController');

Route::get('/compra/historial/detalle/{id}','ComprasController@historialDetalle');
Route::delete('/compra/rollback/{id}','ComprasController@rollback');
Route::get('/compra/historial','ComprasController@historial');
Route::post('/compra/respuestaVPOST','ComprasController@respuestaVPOST');
Route::post('/compra/confirmar','ComprasController@confirmar');
Route::post('/compra/entregado/{id}','ComprasController@entregado');
Route::apiResource('/compra','ComprasController');
Route::apiResource('/sugerencia','SugerenciaController');
Route::apiResource('/user','UserController');

Auth::routes(/*['register'=>false]*/);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/checkUser', 'HomeController@checkUser')->name('checkUser');

Route::get('/prueba','ComprasController@prueba');

//Route::apiResource('thoughts', 'ProductoApiController');
