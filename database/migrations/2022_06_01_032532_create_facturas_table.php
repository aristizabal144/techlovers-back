<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    public function up() {
        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_usuario');
            $table->string('referencia');
            $table->date('fecha');
            $table->integer('id_cliente');
            $table->integer('id_almacen');
            $table->string('descripcion');
            $table->enum('estado',array('pendiente_pago','pagado'));
            $table->integer('total');
            $table->integer('total_descuento');
            $table->integer('faltante_pago');
            $table->integer('valor_descuento');
            $table->integer('valor_flete');
            $table->integer('valor_averias');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
