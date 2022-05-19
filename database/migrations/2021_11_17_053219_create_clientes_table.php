<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{

    public function up(){
        Schema::create('clientes', function (Blueprint $table) {
            // ------------- New schema
            $table->bigIncrements('id');
            $table->string('identificacion');
            $table->string('nombre');
            $table->string('telefono_fijo');
            $table->string('celular');
            $table->string('correo');
            $table->string('descripcion');
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('clientes');
    }
}
