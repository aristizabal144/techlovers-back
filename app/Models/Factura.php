<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    public $table = 'facturas';

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
        'valor_retencion',
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
        return $this->hasMany(ProductosFactura::class, 'id_factura', 'id');
    }
}
