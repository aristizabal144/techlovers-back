<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductosFactura extends Model
{
    public $table = 'productos_facturas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_factura',
        'id_producto',
        'referencia',
        'nombre',
        'cantidad',
        'valor_unidad',
        'valor_total'
    ];

    public function factura() {
        return $this->belongsTo(Factura::class, 'id_factura', 'id');
    }

    public function producto() {
        return $this->belongsTo(Articulo::class, 'id_producto', 'id');
    }
}
