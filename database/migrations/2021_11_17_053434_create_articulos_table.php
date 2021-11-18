<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_categoria_fk');
            $table->string('referencia');
            $table->float('valor_articulo');
            $table->float('valor_articulo_total');
            $table->integer('porcentaje');
            $table->float('valor_porcantaje');
            $table->float('valor_venta');
            $table->string('urlImagen');
            $table->timestamps();
        });
        Schema::table('articulos', function(Blueprint $table){
            $table->foreign('id_categoria_fk')->references('id')->on('categorias');
        });
    }

    public function down(){
        Schema::dropIfExists('articulos');
    }
}
