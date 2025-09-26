<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfirmationsTable extends Migration
{
    public function up() {
        Schema::create('confirmations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_usuario');
            $table->string('referencia');
            $table->date('fecha');
            $table->integer('id_cliente');
            $table->integer('id_almacen');
            $table->string('descripcion');
            $table->integer('total');
            $table->boolean('facturado')->default(false);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('confirmations');
    }
}
