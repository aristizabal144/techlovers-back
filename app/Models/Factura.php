<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    public $table = 'facturas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'referencia',
        'fecha',
        'id_cliente',
        'id_almacen',
        'descripcion',
        'estado',
        'total'
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }

    public function almacen() {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id');
    }

    public function productos() {
        return $this->hasMany(ProductosFactura::class, 'id_factura', 'id');
    }
}
