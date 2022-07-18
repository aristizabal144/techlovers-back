<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    public function up() {
        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('referencia');
            $table->date('fecha');
            $table->integer('id_cliente');
            $table->integer('id_almacen');
            $table->string('descripcion');
            $table->enum('estado',array('pendiente_pago','pagado'));
            $table->integer('total');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
