<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsolidadoArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consolidado_articulos', function (Blueprint $table) {
            $table->id('id_consolidado_articulos');
            $table->unsignedBigInteger('id_consolidado_fk');
            $table->unsignedBigInteger('id_articulo_fk');
            $table->integer('QTY');
            $table->integer('cantidad_cajas');
            $table->float('CBM');
            $table->float('valor_flete');
            $table->float('valor_flete_unidad');
            $table->float('RMB');
            $table->integer('valor_articulo');
            $table->integer('valor_articulo_total');
            $table->integer('porcentaje');
            $table->integer('valor_porcentaje');
            $table->integer('valor_venta');
            $table->timestamps();
        });
        
        Schema::table('consolidado_articulos', function(Blueprint $table){
            $table->foreign('id_consolidado_fk')->references('id_consolidados')->on('consolidados');
            $table->foreign('id_articulo_fk')->references('id_articulos')->on('articulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consolidado_articulos');
    }
}
