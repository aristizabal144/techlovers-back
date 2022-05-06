<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticulosTable extends Migration
{

    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_categoria');
            $table->string('referencia');
            $table->string('nombre');
            $table->integer('valor_entra');
            $table->integer('porcentaje_venta');
            $table->integer('valor_venta');
            $table->integer('cantidad');
            $table->text('descripcion');
            $table->string('urlImagen');
            $table->timestamps();
        });
        Schema::table('articulos', function(Blueprint $table){
            $table->foreign('id_categoria')->references('id')->on('categorias');
        });
    }

    public function down(){
        Schema::dropIfExists('articulos');
    }
}
