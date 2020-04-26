<?php

namespace App\Http\Controllers;

use App\Categoria;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Categoria[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request){
        $categoria_id = $request->input('categoria_id');
        return Categoria::porPadreId($categoria_id);
    }

    public function updateProductosList(Request $request){
        $user = Auth::user();
        if($user && $user->hasAnyRole('admin')){
            $post = $request->all();
            $lista = json_decode($post['lista']);
            $categoria = $post['categoria'];
            Categoria::updateProducto($categoria,$lista);
            return ['success' => true];
        }else{
            return response(['success'=>false,'message'=>'tenes que iniciar sesion como admin'],401);
        }
    }


    public function vista(){

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
