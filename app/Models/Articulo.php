<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    public $table = 'articulos';

    protected $primaryKey = 'idArticulos';

    protected $fillable = [
        'id_articulos', 'id_categoria_fk', 'referencia', 'valor_articulo', 'valor_articulo_total', 'porcentaje', 'valor_porcantaje', 'valor_venta', 'urlImagen'
    ];
}
