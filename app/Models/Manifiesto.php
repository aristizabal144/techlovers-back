<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manifiesto extends Model {

  public $table = 'manifiestos';

  protected $primaryKey = 'id';

  protected $fillable = [
    'id',
    'nombre',
    'fecha',
    'estado',
  ];

}
