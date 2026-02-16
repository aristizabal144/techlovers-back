<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    public $table = 'articulos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_categoria',
        'referencia',
        'codigo_barras',
        'nombre',
        'valor_entra',
        'porcentaje_venta',
        'manifiestsId',
        'numberPageManifiests',
        'valor_venta',
        'cantidad',
        'cantidad_contabilidad',
        'estado',
        'descripcion',
        'urlImagen',
        'is_delete'
    ];

    public function manifiesto() {
        return $this->belongsTo(Manifiesto::class, 'manifiestsId', 'id');
    }
}
