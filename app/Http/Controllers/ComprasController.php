<?php

namespace App\Http\Controllers;

use App\Compra;
use App\CompraProducto;
use App\Pago;
use App\Producto;
use http\Client\Curl\User;
use http\Env\Response;
use http\Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ComprasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request){
        $this->autorizar('user');
        $user = Auth::user();
        /** @var Compra $carrito */
        $carrito = $user->carritoCompra();//->productos();
        $productos = $carrito?$carrito->productos()->where('codigo','!=','delivery'):Producto::query()->where('id','=',-1);
        return $productos->paginate($request->input('cantidad',10),['*'],'page',$request->input('page',1));
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
        $ultimaCompra = $user->carritoCompra();
        $ultimaCompra->agregarProducto($producto,$cantidad);
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
    public function destroy(Compra $compras){

        return response(['message'=>'no, perra'],400);
    }


    public function rollback(Request $request, $id){
        $this->autorizar('financiero');
        $compra = Compra::find($id);
        $pago = $compra->pagos()->where('response_code','00')->orderBy('updated_at','desc')->firstOrFail();
        //inicio de proceso
        $token = md5(env('BANCARD_PRIVATE_KEY') .$pago->id . "rollback"  . '0.00' );
        $response = Http::post(env('BANCARD_URL').'single_buy/rollback',[
            'public_key' => env('BANCARD_PUBLIC_KEY'),
            "operation"=> [
                "token"=> $token,
                "shop_process_id"=> $pago->id,
            ],
        ]);
        if($response->json()['status'] == 'success'){
            $pago->estado = 'roll';
            $compra->estado = 'roll';
            $pago->save();
            $compra->save();
            return $response->json()['messages'][0]['dsc'];
        }else{
            abort(401,$response->json()['messages'][0]['dsc']);
        }
        return $pago;
    }


    public function confirmar(Request $request){
        $this->autorizar('user');
        $user = Auth::user();
        $compraId = $request->validate([
            'compraId' => 'required|integer|min:1',
            'metodo' => 'required|in:1,2,3',
            'delivery' => 'required|boolean'
        ])['compraId'];

        return $request->get('delivery');
        $compra =  Compra::find($compraId);
        //borrar el producto delivery (si existiese)
        $delivery = Producto::query()->where('codigo','delivery')->firstOrFail();   //el delivery
        $compra->productos()->detach($delivery->id);        //sacamos si existiese
        //agregarle el precio de delivery
        $compra->productos()->attach($delivery->id,[
            'cantidad' => 1,
            'precio_actual' => 0
        ]);
        $compra->save();
        //fin precio delivery
        if($user->carritoCompra()->id != $compra->id){
            abort(401,'no tenes autorizacion');
        }
        if($compra->precioTotal() < (env('PAGO_MINIMO') + env('PAGO_DELIVERY'))){
            abort(401,'Pago minimo es '.env('PAGO_MINIMO'));
        }
        $compra->actualizarPreciosIntermedios();
        $pago = new Pago();
        $pago->compra()->associate($compra);
        $pago->tipo_pago = ($metodoPago = $request->get('metodo'));
        $pago->save();
        return $pago->procesarPago();
    }

    public function respuestaVPOST(Request $request){
        $operacion = $request->all();
        $pagoId = $operacion['operation']['shop_process_id'];
        $pago = Pago::findOrFail($pagoId);
        $pago->dsc = json_encode($operacion);
        try{
            $responseCode = $operacion['operation']['response_code'];
            $compra = $pago->compra;
            if($responseCode == '00'){
                $compra->estado = 'x1'; //que ya se pago con metodo 1
                $compra->pagado = 1;
            }else{
                $compra->estado = 'd1'; //que se denego el pago
                $compra->pagado = 0;
            }
            $compra->save();
            $authorization = $operacion['operation']['authorization_number'];
            $ticket = $operacion['operation']['ticket_number'];
            $responseDesc = $operacion['operation']['response_description'];
            $precio = floatval($operacion['operation']['amount']);
            $pago->precio = $precio;
            $pago->ticket = $ticket;
            $pago->response_code = $responseCode;
            $pago->response_desc = $responseDesc;
            $pago->authorization = $authorization;
            $pago->save();
        }catch (\Exception $e){
            $pago->save();
            throw $e;
        }
    }

    public function historial(Request $request){
        $request->validate([
            'cantidad' => 'integer|max:40',    //por pagina
            'page'=>'integer',          //pagina actual
        ]);
        if(($user = Auth::user()) && $user->hasRole('financiero')){
            $pagos = Compra::detallePagosUsuarios();
        }else{
            /** @var \App\User $user */
            $user = Auth::user();
            $this->autorizar('user');
            $pagos = Compra::detallePagosPersonal($user->id);
        }
        return $pagos->paginate($request->get('cantidad',20),['*'],'page',$request->get('page',1));
    }

    public function historialDetalle(Request $request, $id){
        $request->validate([
            'cantidad' => 'integer|max:40',    //por pagina
            'page'=>'integer',          //pagina actual
        ]);
        $this->autorizar(['user','financiero']);
        $user = Auth::user();
        $compra = Compra::find($id);
        if ($compra->user->id != $user->id && !$user->hasRole('financiero')) {
            abort(401, 'Se cruzaron datos');
        }
        $productos = $compra->productos();
        return $productos->paginate($request->get('cantidad',20),['*'],'page',$request->get('page',1));
    }
}

