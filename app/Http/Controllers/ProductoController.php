<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Producto;
use App\TipoRespuesta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller{

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request){
        $arrayRetorno = [];
        $categoria_id = $request->input('categoria_id');
        $palabraClave = $request->input('busqueda');
        $limiteInferior = intval($request->input('limite_inferior',0));
        $cantidad = intval($request->input('cantidad',10));
        if($palabraClave != null){
            $arrayRetorno['productos'] = Producto::porPalabraClave($palabraClave,$categoria_id,$limiteInferior,$cantidad);
            $arrayRetorno['cantidad'] = Producto::getCantidad($palabraClave);
        }else if($categoria_id !== null){
            $arrayRetorno['productos'] = Producto::porCategoria($categoria_id);
        }else{
            $arrayRetorno['productos'] = Producto::porLimite($limiteInferior,$cantidad);
            $arrayRetorno['cantidad'] = Producto::getCantidad(null);
        }
        return $arrayRetorno;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('producto.editar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Request $request){
        return [
            'op'=>'store',
            'data' => $request->all()
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Producto $producto)
    {
        return view('producto.visor',[
            'producto' => $producto
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Producto $producto)
    {
        return view('producto.editar',[
            'producto' => $producto
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Producto $producto
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto){
        $user = Auth::user();
        if($user && $user->hasAnyRole('admin')){
            $producto->update($request->all());
            return response(['success'=>true],200);
        }else{
            return response(['success'=>false,'message'=>'tenes que iniciar sesion como admin'],401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto){
        $user = Auth::user();
        if($user && $user->hasAnyRole('admin')){
            try {
                $producto->delete();
                return response(['success'=>true],200);
            } catch (\Exception $e) {
                return response(['success'=>false,'message'=>$e->getMessage()],401);
            }
        }else{
            return response(['success'=>false,'message'=>'tenes que iniciar sesion como admin'],401);
        }
    }

    public function thumbnail(Request $request,$id){
        $validatedData = $request->validate([
            'thumbnail' => 'required|mimes:jpeg|dimensions:min_width=100,min_height=100,max_width=500,max_height=500',
        ]);
        /** @var Producto $producto */
        $producto = Producto::find($id);
        $producto->setThumbnail($validatedData['thumbnail']->get());
        return response(['success'=>true,'message'=>'Se alzo el archivo'],200);
    }
}
