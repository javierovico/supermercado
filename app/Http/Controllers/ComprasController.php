<?php

namespace App\Http\Controllers;

use App\Compra;
use App\CompraProducto;
use App\Pago;
use App\Producto;
use http\Client\Curl\User;
use http\Env\Response;
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
        $productos = $user->compras()->where('pagado',false)->first()->productos();
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

    public function confirmar(Request $request){
        $this->autorizar('user');
        $user = Auth::user();
        $compraId = $request->validate([
            'compraId' => 'required|integer|min:1'
        ])['compraId'];
        $compra =  Compra::find($compraId);
        if($user->carritoCompra()->id != $compra->id){
            return response(['success'=>false,'message'=>'no tenes autorizacion']);
        }
        $pago = new Pago(['precio' => $compra->precioTotal()]);
        $pago->compra()->associate($compra);
        $pago->save();
        $precioTotalString = number_format($compra->precioTotal(),2,'.','');
        $token = md5(env('BANCARD_PRIVATE_KEY') .$pago->id . $precioTotalString  . 'PYG' );
        $pago->token = $token;
        $response = Http::post('https://vpos.infonet.com.py:8888/vpos/api/0.3/single_buy',[
            'public_key' => env('BANCARD_PUBLIC_KEY'),
            "operation.operation"=> [
                "operation.token"=> $token,
                "operation.shop_process_id"=> $pago->id,
                "operation.amount"=> $precioTotalString,
                "operation.currency"=> "PYG",
                "operation.additional_data"=> "",
                "operation.description"=> "Compra de " . $user->name,
                "operation.return_url"=> env('APP_URL'),
                "operation.cancel_url"=> env('APP_URL')
            ],
//            "operation.test_client"=> true,
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
            return response(['message'=> $respuesta['messages'][0]['dsc']],400);
        }
        $pago->process_id = $respuesta['process_id'];
        $pago->save();

        return ['success'=>true,'process_id'=>$pago->process_id];
    }

    public function respuestaVPOST(Request $request){
        $pago = Pago::find($request->get('operation.shop_process_id'));
        $pago->dsc = $request->all();
        $pago->save();
        $request->validate([
            'operation' => 'required|json',
            'operation.token' => 'required|string',
            "operation.shop_process_id"=> 'required|integer',
            "operation.response"=> "required|string",
            "operation.response_details"=> "required|string",
            "operation.amount"=> "required|numeric",
            "operation.currency"=> "required|string",
            "operation.authorization_number"=> "string",
            "operation.ticket_number"=> "required|string",
            "operation.response_code"=> "required|integer",
            "operation.response_description"=> "required|string",
            "operation.extended_response_description"=> "required|string",
            "operation.security_information"=> "required|json",
            "operation.security_information.customer_ip"=> "181.122.164.5",
            "operation.security_information.card_source"=> "L",
            "operation.security_information.card_country"=> "PARAGUAY",
            "operation.security_information.version"=> "0.3",
            "operation.security_information.risk_index"=> 0
        ]);
    }
}
