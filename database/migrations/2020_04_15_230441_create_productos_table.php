<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',100)->unique();
            $table->integer('impuesto')->default(10);
            $table->integer('descuento')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('linea')->default(0);
            $table->text('nombre');
            $table->text('thumbnail')->nullable();
            $table->text('contenido')->nullable();
            $table->unsignedBigInteger('tipo_medida_producto_id');
            $table->foreign('tipo_medida_producto_id')->references('id')->on('tipo_medida_productos')->onDelete('cascade');
            $table->integer('precio');
            $table->integer('precio_mayorista')->default(0);
            $table->integer('precio_costo')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
