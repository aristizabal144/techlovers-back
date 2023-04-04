<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaContabilidad extends Model
{
    public $table = 'factura_contabilidad';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_usuario',
        'referencia',
        'fecha',
        'id_cliente',
        'id_almacen',
        'descripcion',
        'estado',
        'total',
        'total_descuento',
        'faltante_pago',
        'valor_descuento',
        'valor_flete',
        'valor_averias',
        'fecha_pago'
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }

    public function encargado() {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function almacen() {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id');
    }

    public function productos() {
        return $this->hasMany(ProductosFacturaContabilidad::class, 'id_factura', 'id');
    }
}
