<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_usuario');
            $table->string('id_factura');
            $table->string('id_cliente');
            $table->string('id_almacen');
            $table->string('referencia');
            $table->string('fecha');
            $table->string('descripcion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('devoluciones');
    }
}
