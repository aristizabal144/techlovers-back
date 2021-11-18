<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{

    public function up(){
        Schema::create('facturas', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_cliente_fk');
            $table->integer('numero_cotizacion');
            $table->date('fecha');
            //Preguntarle a cristian cual es la precision que se le asignara.
            $table->float('total_descuento');
            $table->float('total_factura');
            $table->text('descripcion');
            $table->timestamps();
        });
        Schema::table('facturas', function(Blueprint $table){
            $table->foreign('id_cliente_fk')->references('id')->on('clientes');
        });
    }

    public function down(){
        Schema::dropIfExists('facturas');
    }
}
