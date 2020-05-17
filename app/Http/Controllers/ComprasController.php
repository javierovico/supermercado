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
        $productos = $user->compras()->where('pagado',false)->first();//->productos();
        $productos = $productos?$productos->productos():Producto::query()->where('id','=',-1);
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
    public function destroy(Compra $compras){

        return response(['message'=>'no, perra'],400);
    }

//    public function prueba(Request $request){
//        $privateKey = 'CFpJf+qjxCF0$yKeSerPHgd+mPS1$AgIOR3qRt3U';
//        $shopProccessId = '9';
//        $amount = '10000.00';
//        $currency = 'PYG';
//        $tokenPagoSimple = md5($privateKey . $shopProccessId . $amount . $currency );
//        $cardId = '304';
//        $userId = '478';
//        $requestNuevo = 'request_new_card';
//        $tokenNuevoCard = md5($privateKey .$cardId . $userId . $requestNuevo );
//        $response = Http::post('https://vpos.infonet.com.py:8888/vpos/api/0.3/cards/new',[
//            "operation.public_key"=> "d3uY6f5jkDBa0AtPGcTeaVyzoQlFlHCH",
//            'operation' => [
//                "operation.token" => $tokenNuevoCard,
//                'card_id' => $cardId,
//                'user_id' => $userId,
//                'user_cell_phone' => '0971639734',
//                'user_mail' => 'javierovico@gmail.com',
//                'shop_process_id' => $shopProccessId,
//                'amount' => $amount,
//                'currency' => $currency,
//                'descripcion' => 'probando',
//                'return_url' => 'http://kamaleon360.com',
//                'cancel_url' => 'http://kamaleon360.com',
//            ]
//        ]);
//        echo $response->body();
//
//        return;
//
//        $success =  $response->status() == 200;
//        $processId = $success?$response->json()['process_id']:null;
//        if($success){
//            return view('prueba',[
//                'success' => $success,
//                'processId' => $processId,
//            ]);
//        }else{
//            echo $response->body();
//        }
//    }

//    private function rollBack($id){
//        $token = md5(env('BANCARD_PRIVATE_KEY') .$id .  'rollback' . '0.00' );
//        $response = Http::post('https://vpos.infonet.com.py:8888/vpos/api/0.3/single_buy/rollback',[
//            'public_key' => env('BANCARD_PUBLIC_KEY'),
//            "operation.operation"=> [
//                "operation.token"=> $token,
//                "operation.shop_process_id"=> $id,
//                "operation.amount"=> '0.00',
//                "operation.currency"=> "PYG",
//                "operation.additional_data"=> "",
//                "operation.description"=> "Compra de ",
//                "operation.return_url"=> env('APP_URL'),
//                "operation.cancel_url"=> env('APP_URL')
//            ],
//            "operation.test_client"=> true,
//        ]);
//        $respuesta = $response->json();
//        if($respuesta == null){
//            abort(400,$response->body());
//        }
//        if($respuesta['status'] == 'error'){
//            abort(400,$response->body());
//        }
//    }


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
            'compraId' => 'required|integer|min:1'
        ])['compraId'];
        $compra =  Compra::find($compraId);
        if($user->carritoCompra()->id != $compra->id){
            abort(401,'no tenes autorizacion');
        }
        $compra->actualizarPreciosIntermedios();
        $pago = new Pago();
        $pago->compra()->associate($compra);
        $pago->save();
        $precioTotalString = number_format($compra->precioTotal(),2,'.','');
        $token = md5(env('BANCARD_PRIVATE_KEY') .$pago->id . $precioTotalString  . 'PYG' );
        $pago->token = $token;
        $response = Http::post(env('BANCARD_URL').'single_buy',[
            'public_key' => env('BANCARD_PUBLIC_KEY'),
            "operation"=> [
                "token"=> $token,
                "shop_process_id"=> $pago->id,
                "amount"=> $precioTotalString,
                "currency"=> "PYG",
                "additional_data"=> "",
                "description"=> "Compra de " . $user->name,
                "return_url"=> env('APP_URL').'/carrito/historial',      //retorna ?status=payment_success si funciono
                "cancel_url"=> env('APP_URL').'/carrito/historial',
            ],
            "test_client"=> true,
        ]);
        $respuesta = $response->json();
        if($respuesta == null){
            $pago->key = 'NO_ACCESS_VPOS';
            $pago->save();
            return response(['message'=>'no se pudo acceder a bancard'],400);
        }
        if($respuesta['status'] != 'success'){
            $pago->dsc = $respuesta['messages'][0]['dsc'];
            $pago->key =$respuesta['messages'][0]['key'];
            $pago->save();
            return response(['message'=> $respuesta['messages'][0]['key'], 'respuesta' => $respuesta],400);
        }
        $pago->process_id = $respuesta['process_id'];
        $pago->save();

        return ['success'=>true,'process_id'=>$pago->process_id];
    }

    public function respuestaVPOST(Request $request){
        $operacion = $request->all();
        $pagoId = $operacion['operation']['shop_process_id'];
        $pago = Pago::findOrFail($pagoId);
        $pago->dsc = json_encode($operacion);
        try{
            $responseCode = $operacion['operation']['response_code'];
            if($responseCode == '00'){
                $compra = $pago->compra;
                $compra->pagado = 1;
                $compra->save();
            }
            $authorization = $operacion['operation']['authorization_number'];
            $ticket = $operacion['operation']['ticket_number'];
            $responseDesc = $operacion['operation']['response_description'];
            $precio = $operacion['operation']['amount'];
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
            $pagos = Compra::query()->where('pagado',1)->with('user')->orderBy('updated_at','desc');
        }else{
            $this->autorizar('user');
            $user = Auth::user();
            $pagos = $user->compras()->where('pagado',1)->orderBy('updated_at','desc');
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

