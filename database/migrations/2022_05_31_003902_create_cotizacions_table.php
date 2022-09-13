<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionsTable extends Migration
{
    public function up() {
        Schema::create('cotizacions', function (Blueprint $table) {
            $table->bigIncrements('id');
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
        Schema::dropIfExists('cotizacions');
    }
}
