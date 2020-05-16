<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('process_id',50)->nullable();
            $table->unsignedBigInteger('compra_id');
            $table->string('token',100)->nullable();
            $table->float('precio')->nullable();
            $table->string('currency')->default('PYG');
            $table->foreign('compra_id')->references('id')->on('compras');  //on delete error
            $table->string('key',30)->nullable();
            $table->text('dsc')->nullable();
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
        Schema::dropIfExists('pagos');
    }
}
