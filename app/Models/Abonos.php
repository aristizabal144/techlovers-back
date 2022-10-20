<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abonos extends Model {

  public $table = 'abonos';

  protected $primaryKey = 'id';

  protected $fillable = [
    'id',
    'id_factura',
    'estado',
    'fecha',
    'valor',
    'descripcion'
  ];

  public function factura() {
    return $this->belongsTo(Factura::class, 'id_factura', 'id');
  }
}
