<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbonosTable extends Migration {
    public function up() {
        Schema::create('abonos', function (Blueprint $table) {
            $table->id('id');
            $table->integer('id_factura');
            $table->enum('estado', array('efectivo', 'transferencia'));
            $table->date('fecha');
            $table->integer('valor');
            $table->string('descripcion');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('abonos');
    }
}
