<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Vales extends Migration
{
    public function up()
    {
        Schema::create('vales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha');
            $table->date('fecha_pago')->nullable();
            $table->integer('valor');
            $table->integer('faltante_pago');
            $table->unsignedBigInteger('id_usuario');
            $table->enum('estado', array('pagado', 'pendiente'));
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vales');
    }
}
