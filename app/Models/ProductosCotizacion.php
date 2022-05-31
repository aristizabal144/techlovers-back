<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductosCotizacion extends Model
{
    public $table = 'productos_cotizacions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_cotizacion',
        'id_producto',
        'referencia',
        'nombre',
        'cantidad_cotizacion',
        'valor_unidad',
        'valor_total'
    ];

    public function cotizacion() {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion', 'id');
    }

    public function producto() {
        return $this->belongsTo(Articulo::class, 'id_producto', 'id');
    }
}
