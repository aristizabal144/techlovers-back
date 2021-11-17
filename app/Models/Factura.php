<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    public $table = 'facturas';

    protected $primaryKey = "id_factura";

    protected $fillable = [
        'id_factura', 'id_cliente_fk', 'numero_cotizacion', 'fecha', 'total_descuento', 'total_factura', 'descripcion'
    ];
}
