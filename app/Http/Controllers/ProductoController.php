<?php

namespace App\Http\Controllers;

use App\Producto;
use App\TipoRespuesta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return array
     */
    public function update(Request $request, Producto $producto){
        $respuesta = new TipoRespuesta();
        $respuesta->setDato(['producto'=>$producto,'request'=>$request->all()]);
        return $respuesta->toJSON();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        return 'destroy';
    }
}
