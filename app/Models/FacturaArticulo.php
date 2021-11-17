<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaArticulos extends Model
{
    public $table = 'factura_articulos';

    protected $primaryKey = 'id_factura_articulos';

    protected $fillable = [
        'id_factura_articulos','id_factura_fk','id_articulo_fk','cantidad','valor_unitario','valor_descuento','valor_total','porcentaje_descuento'
    ];
}
