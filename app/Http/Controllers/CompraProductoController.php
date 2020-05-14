<?php

namespace App\Http\Controllers;

use App\CompraProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompraProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\CompraProducto  $compraProducto
     * @return \Illuminate\Http\Response
     */
    public function show(CompraProducto $compraProducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CompraProducto  $compraProducto
     * @return \Illuminate\Http\Response
     */
    public function edit(CompraProducto $compraProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CompraProducto  $compraProducto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompraProducto $compraProducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\CompraProducto $compraProducto
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(CompraProducto $compraProducto){
        $this->autorizar('user');
        $user = Auth::user();
        if($compraProducto->compra->user->id === $user->id){
            $compraProducto->delete();
            return response(['success'=>true,'message'=>'se borro']);
        }else{
            return response(['success'=>false,'message'=>'No podes actuar sobre otra cuenta, pequenho pequenhuelo :v']);
        }

    }
}
