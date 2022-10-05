<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    public $table = 'devoluciones';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_factura',
        'id_cliente',
        'id_almacen',
        'referencia',
        'fecha',
        'descripcion'
    ];

    public function factura() {
        return $this->belongsTo(Factura::class, 'id_factura', 'id');
    }
    public function productos() {
        return $this->hasMany(ProductosCotizacion::class, 'id_cotizacion', 'id');
    }

}

