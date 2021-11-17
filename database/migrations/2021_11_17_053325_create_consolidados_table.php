<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsolidadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consolidados', function (Blueprint $table) {
            $table->id('id_consolidados');
            $table->string('referencia_consolidado');
            $table->date('fecha_pedido');
            $table->date('fecha_salida');
            $table->date('fecha_llegada');
            $table->integer('valor_flete');
            $table->integer('valor_RMB');
            $table->integer('metros_cubicos');
            $table->integer('subtotal_RMB');
            $table->integer('total');
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
        Schema::dropIfExists('consolidados');
    }
}
