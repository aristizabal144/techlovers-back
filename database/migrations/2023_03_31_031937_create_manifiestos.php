<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManifiestos extends Migration
{
    public function up() {
        Schema::create('manifiestos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('fecha');
            $table->boolean('estado');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos_contabilidad');
    }
}
