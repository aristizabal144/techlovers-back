<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductosFacturaContabilidad extends Model
{
    public $table = 'productos_contabilidad';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_factura',
        'id_producto',
        'referencia',
        'nombre',
        'cantidad',
        'valor_unidad',
        'valor_iva',
        'valor_total_unidad',
        'valor_total'
    ];

    public function factura() {
        return $this->belongsTo(Factura::class, 'id_factura', 'id');
    }

    public function producto() {
        return $this->belongsTo(Articulo::class, 'id_producto', 'id');
    }
}

