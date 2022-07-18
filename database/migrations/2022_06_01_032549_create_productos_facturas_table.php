<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosFacturasTable extends Migration
{
    public function up() {
        Schema::create('productos_facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_factura');
            $table->integer('id_producto');
            $table->string('referencia');
            $table->string('nombre');
            $table->integer('cantidad');
            $table->integer('valor_unidad');
            $table->integer('valor_total');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos_facturas');
    }
}
