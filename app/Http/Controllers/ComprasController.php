<?php

namespace App\Http\Controllers;

use App\Compra;
use App\CompraProducto;
use App\Producto;
use http\Env\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComprasController extends Controller
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
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request){
        $this->autorizar('user');
        $dato = $request->validate([
            'cantidad' => 'required|numeric|min:0.1',
            'productoId' => 'required|integer|min:1'
        ]);
        $producto = Producto::find($dato['productoId']);
        $cantidad = $dato['cantidad'];
        if($producto->tipoMedida->tipo == 'c/u' && $cantidad != intval($cantidad)){
            return response(['message' => 'Solo  valores enteros son admitidos','success'=>false],400);
        }else if($producto->tipoMedida->tipo != 'el Kg' && $producto->tipoMedida->tipo != 'c/u'){
            return response(['message' => 'Producto no disponible','success'=>false],400);
        }
        //FIN VALIDACION
        $user = Auth::user();
        $ultimaCompra = Compra::query()->whereHas('user',function (Builder $q) use ($user) {
            $q->where('id','=',$user->id);
        })->where('pagado',false)->orderBy('updated_at','desc')->first();
        if($ultimaCompra == null){
            $ultimaCompra = new Compra();
            $ultimaCompra->user()->associate($user);
            $ultimaCompra->save();
        }
        $compraProducto = CompraProducto::query()
            ->where('compra_id','=',$ultimaCompra->id)
            ->where('producto_id','=',$producto->id)->first();
        if($compraProducto == null){
            $ultimaCompra->productos()->attach($producto->id,[
                'cantidad' => $cantidad,
                'precio_actual' => 0
            ]);
            $ultimaCompra->save();
        }else{
            $compraProducto->cantidad += $cantidad;
            $compraProducto->save();
        }
        return [
            'success' => true,
            'message' => 'guardado'
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Compra  $compras
     * @return \Illuminate\Http\Response
     */
    public function show(Compra $compras)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Compra  $compras
     * @return \Illuminate\Http\Response
     */
    public function edit(Compra $compras)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Compra  $compras
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compra $compras)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Compra  $compras
     * @return \Illuminate\Http\Response
     */
    public function destroy(Compra $compras)
    {
        //
    }
}
