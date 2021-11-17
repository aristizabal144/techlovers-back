<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public $table = 'categorias';

    protected $primaryKey = 'id_categorias';

    protected $fillable = [
        'id_categorias', 'nombre', 'descripcion', 'estado'
    ];
}
