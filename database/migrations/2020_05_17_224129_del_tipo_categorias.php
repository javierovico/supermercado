<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DelTipoCategorias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('tipo_categorias');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::create('tipo_categorias', function (Blueprint $table) {
            $table->id();
            $table->text('tipo');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }
}
