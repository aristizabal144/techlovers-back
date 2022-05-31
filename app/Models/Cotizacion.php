<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    public $table = 'cotizacions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_cliente',
        'id_almacen',
        'referencia',
        'fecha',
        'descripcion',
        'total'
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }
    
    public function almacen() {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id');
    }

    public function productos() {
        return $this->hasMany(ProductosCotizacion::class, 'id_cotizacion', 'id');
    }
}
