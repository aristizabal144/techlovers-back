<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model{

    public $table = 'categorias';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nombre', 'descripcion', 'estado'
    ];
}
