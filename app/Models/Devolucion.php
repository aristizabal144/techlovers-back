<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    public $table = 'devoluciones';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_usuario',
        'id_factura',
        'id_cliente',
        'id_almacen',
        'referencia',
        'fecha',
        'descripcion'
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }
    public function almacen() {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id');
    }
    public function factura() {
        return $this->belongsTo(Factura::class, 'id_factura', 'id');
    }
    public function productos() {
        return $this->hasMany(ProductosCotizacion::class, 'id_cotizacion', 'id');
    }

}

