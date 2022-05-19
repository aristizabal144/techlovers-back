<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{

    public function up(){
        Schema::create('clientes', function (Blueprint $table) {
            // ------------- New schema
            $table->id('id');
            $table->string('identificacion');
            $table->string('nombre');
            $table->string('telefono_fijo');
            $table->string('celular');
            $table->string('correo');
            $table->string('descripcion');
            $table->timestamps();
            
            // ------------- Old Schema
            /* $table->id('id');
            $table->string('nombre');
            $table->string('razon_social');
            $table->string('nit_cc')->unique();
            $table->string('telefono');
            $table->string('ciudad');
            $table->string('barrio');
            $table->string('direccion');
            $table->string('encargado');
            $table->string('celular', 11);
            $table->string('correo')->unique();
            $table->string('url_imagen'); */
        });
    }

    public function down(){
        Schema::dropIfExists('clientes');
    }
}
