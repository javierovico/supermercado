<?php

use Illuminate\Database\Seeder;

class ProductoDeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $prodNuevo = new \App\Producto();
        $prodNuevo->codigo = 'delivery';
        $prodNuevo->impuesto = 0 ;
        $prodNuevo->nombre = 'Precio Delivery';
        $prodNuevo->tipo_medida_producto_id = 1;
        $prodNuevo->precio = env('PAGO_DELIVERY');
        $prodNuevo->save();
    }
}
