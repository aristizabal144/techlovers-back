<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vale extends Model {

  public $table = 'vales';

  protected $primaryKey = 'id';

  protected $fillable = [
    'id',
    'fecha',
    'valor',
    'id_usuario',
    'estado'
  ];

public function encargado() {
    return $this->belongsTo(User::class, 'id_usuario', 'id');
}
  
}