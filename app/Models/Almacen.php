<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    public $table = 'almacenes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'nit',
        'nombre',
        'encargado',
        'ciudad',
        'direccion',
        'telefono',
        'descripcion'
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }
}
