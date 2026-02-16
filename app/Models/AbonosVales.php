<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbonosVales extends Model {

  public $table = 'abonos_vales';

  protected $primaryKey = 'id';

  protected $fillable = [
    'id',
    'id_vale',
    'estado',
    'fecha',
    'valor',
    'descripcion'
  ];

  public function vale() {
    return $this->belongsTo(Vale::class, 'id_vale', 'id');
  }
}
