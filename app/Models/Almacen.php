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
        'direccion',
        'telefono',
        'descripcion'
    ];

    public function client() {
        return $this->hasOne(Cliente::class, 'id', 'id_cliente');
    }
}
