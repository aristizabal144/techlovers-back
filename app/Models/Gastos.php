<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gastos extends Model {

  public $table = 'gastos';

  protected $primaryKey = 'id';

  protected $fillable = [
    'id',
    'fecha',
    'valor',
    'metodo_pago',
    'descripcion',
  ];
  
}
