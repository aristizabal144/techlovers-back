<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public $table = 'clientes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nombre', 'razon_social', 'nit_cc', 'telefono', 'ciudad', 'barrio', 'direccion', 'encargado', 'celular', 'correo', 'url_imagen'
    ];
}
