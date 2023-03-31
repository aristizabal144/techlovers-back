<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticuloContabilidad extends Migration
{

    public function up()
    {
        Schema::create('articulo_contabilidad', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_categoria');
            $table->string('referencia');
            $table->string('codigo_barras');
            $table->string('nombre');
            $table->integer('valor_entra');
            $table->float('porcentaje_venta');
            $table->integer('valor_venta');
            $table->integer('valor_iva_venta');
            $table->integer('valor_total_venta');
            $table->integer('cantidad');
            $table->boolean('estado');
            $table->text('descripcion');
            $table->string('urlImagen');
            $table->integer('ultimoMovimiento')->default(0);
            $table->timestamps();
        });
        Schema::table('articulo_contabilidad', function (Blueprint $table) {
            $table->foreign('id_categoria')->references('id')->on('categorias');
        });
    }

    public function down()
    {
        Schema::dropIfExists('articulo_contabilidad');
    }
}
