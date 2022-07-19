<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    public $table = 'almacenes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'id_cliente',
        'nit',
        'nombre',
        'encargado',
        'ciudad',
        'barrio',
        'direccion',
        'telefono',
        'descripcion'
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }
}
