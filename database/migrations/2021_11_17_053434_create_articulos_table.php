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
            $table->string('codigo_barras');
            $table->string('nombre');
            $table->integer('valor_entra');
            $table->float('porcentaje_venta');
            $table->integer('valor_venta');
            $table->integer('cantidad');
            $table->integer('cantidad_contabilidad');
            $table->boolean('estado');
            $table->text('descripcion');
            $table->string('urlImagen');
            $table->integer('ultimoMovimiento')->default(0);
            $table->boolean('is_delete')->nullable()->default(false);;
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
