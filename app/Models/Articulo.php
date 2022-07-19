<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    public $table = 'articulos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'id_categoria', 'referencia', 'nombre', 'valor_entra', 'porcentaje_venta', 'valor_venta', 'cantidad', 'descripcion', 'urlImagen', 'ultimoMovimiento'
    ];
}
