<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposExtrasToPagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('authorization',200)->nullable();
            $table->string('ticket',200)->nullable();
            $table->string('response_code',5)->nullable();
            $table->text('response_desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn('authorization');
            $table->dropColumn('ticket');
            $table->dropColumn('responseCode');
            $table->dropColumn('responseDesc');
        });
    }
}
