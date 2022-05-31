<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosCotizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_cotizacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_cotizacion');
            $table->integer('id_producto');
            $table->string('referencia');
            $table->string('nombre');
            $table->integer('cantidad_cotizacion');
            $table->integer('valor_unidad');
            $table->integer('valor_total');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos_cotizacions');
    }
}
