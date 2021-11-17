<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsolidadoArticulo extends Model
{
    public $table = 'consolidado_articulos';

    protected $primaryKey = 'id_consolidado_articulos';

    protected $fillable = [
        'id_consolidado_articulos', 'id_consolidado_fk', 'id_articulo_fk', 'QTY', 'cantidad_cajas', 'CBM', 'valor_flete'. 'valor_flete_unidad', 'RMB', 'valor_articulo', 'valor_articulo_total', 'porcentaje', 'valor_porcentaje', 'valor_venta'
    ];
}
