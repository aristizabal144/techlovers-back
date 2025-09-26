<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosConfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_confirmations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_confirmation');
            $table->integer('id_producto');
            $table->string('referencia');
            $table->string('nombre');
            $table->integer('cantidad_confirmacion');
            $table->integer('valor_unidad');
            $table->integer('valor_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos_confirmations');
    }
}
