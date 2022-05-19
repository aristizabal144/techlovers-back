<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlmacenesTable extends Migration
{

    public function up(){
        Schema::create('almacenes', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('id_cliente');
            $table->string('nit');
            $table->string('nombre');
            $table->string('encargado');
            $table->string('ciudad');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('descripcion');
            $table->timestamps();
            
        });
    }

    public function down(){
        Schema::dropIfExists('almacenes');
    }
}
