<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    public $table = 'devoluciones';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'referencia',
        'fecha',
        'nombre',
        'cantidad',
        'descripcion'
    ];

}

