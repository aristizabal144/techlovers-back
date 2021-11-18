<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_articulos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_factura_fk');
            $table->unsignedBigInteger('id_articulo_fk');
            $table->integer('cantidad');
            $table->float('valor_unitario');
            $table->float('valor_descuento');
            $table->float('valor_total');
            $table->integer('porcentaje_descuento');
            $table->timestamps();
        });
        Schema::table('factura_articulos', function(Blueprint $table){
            $table->foreign('id_factura_fk')->references('id')->on('facturas');
            $table->foreign('id_articulo_fk')->references('id')->on('articulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factura_articulos');
    }
}
