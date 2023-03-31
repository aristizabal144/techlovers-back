<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloContabilidad extends Model
{
  public $table = 'articulos_contabilidad';

  protected $primaryKey = 'id';

  protected $fillable = [
    'id',
    'id_categoria',
    'referencia',
    'codigo_barras',
    'nombre',
    'valor_entra',
    'porcentaje_venta',
    'valor_venta',
    'valor_iva_venta',
    'valor_total_venta',
    'cantidad',
    'estado',
    'descripcion',
    'urlImagen'
  ];
}
