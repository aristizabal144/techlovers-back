<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public $table = 'clientes';

    protected $primaryKey = 'id';

    // ------------- New schema
    protected $fillable = [
        'id',
        'identificacion',
        'nombre',
        'telefono_fijo',
        'celular',
        'correo',
        'descripcion'
    ];

    // ------------- Old Schema
    /* protected $fillable = [
        'id',
        'nombre',
        'razon_social',
        'nit_cc',
        'telefono',
        'ciudad',
        'barrio',
        'direccion',
        'encargado',
        'celular',
        'correo',
        'url_imagen'
    ]; */
}
