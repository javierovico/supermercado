<?php

namespace App;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

/**
 * @method Pago find($get)
 * @method static findOrFail($pagoId)
 */
class Pago extends Model{

    protected $fillable = ['precio'];

    public function compra(){
        return $this->belongsTo(Compra::class);
    }

    public function procesarPago(){
        $this->response_code = 'x' . $this->tipo_pago;    //dennotara no pagado (x1, x2, x3 ...)
        $this->save();
        switch ($this->tipo_pago){
            case 1:
                return $this->procesarPost();
                break;
            case 2:
            case 3:
                return $this->procesarContraentrega();
                break;
            default:
                return response([],400);
                break;
        }
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function procesarPost(){
        /** @var Compra $compra */
        $compra = $this->compra;
        $user = $compra->user;
        $precioTotalString = number_format($compra->precioTotal(),2,'.','');
        $token = md5(env('BANCARD_PRIVATE_KEY') .$this->id . $precioTotalString  . 'PYG' );
        $this->token = $token;
        $response = Http::post(env('BANCARD_URL').'single_buy',[
            'public_key' => env('BANCARD_PUBLIC_KEY'),
            "operation"=> [
                "token"=> $token,
                "shop_process_id"=> $this->id,
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
            $this->key = 'NO_ACCESS_VPOS';
            $this->save();
            return response(['message'=>'no se pudo acceder a bancard'],400);
        }
        if($respuesta['status'] != 'success'){
            $this->dsc = $respuesta['messages'][0]['dsc'];
            $this->key =$respuesta['messages'][0]['key'];
            $this->save();
            return response(['message'=> $respuesta['messages'][0]['key'], 'respuesta' => $respuesta],400);
        }
        $this->process_id = $respuesta['process_id'];
        $this->save();
        return response(['metodoPago' => $this->tipo_pago, 'success'=>true,'process_id'=>$this->process_id],200);
    }

    public function procesarContraentrega(){
        return response(['metodoPago' => $this->tipo_pago, 'success' => true]);
    }

}
