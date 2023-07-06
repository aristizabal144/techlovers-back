<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaContabilidad extends Migration
{
    public function up() {
        Schema::create('factura_contabilidad', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_usuario');
            $table->string('referencia');
            $table->date('fecha');
            $table->integer('id_cliente');
            $table->integer('id_almacen');
            $table->string('descripcion');
            $table->enum('estado',array('pendiente_facturar','facturado'));
            $table->integer('total');
            $table->integer('total_descuento');
            $table->integer('iva');
            $table->integer('total_iva');
            $table->integer('faltante_pago');
            $table->integer('valor_descuento');
            $table->integer('valor_flete');
            $table->integer('valor_averias');
            $table->integer('valor_retencion');
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('factura_contabilidad');
    }
}
